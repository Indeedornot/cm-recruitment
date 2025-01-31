<?php

namespace App\Contract\PhoneNumber\Form\Type;


use App\Contract\PhoneNumber\Form\DataTransformer\PhoneNumberToArrayTransformer;
use App\Contract\PhoneNumber\Form\DataTransformer\PhoneNumberToStringTransformer;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Languages;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Phone number form type.
 */
class PhoneNumberType extends AbstractType
{
    public const string WIDGET_SINGLE_TEXT = 'single_text';
    public const string WIDGET_COUNTRY_CHOICE = 'country_choice';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (self::WIDGET_COUNTRY_CHOICE === $options['widget']) {
            $util = PhoneNumberUtil::getInstance();

            $countries = array();

            if (is_array($options['country_choices'])) {
                foreach ($options['country_choices'] as $country) {
                    $code = $util->getCountryCodeForRegion($country);

                    if ($code) {
                        $countries[$country] = $code;
                    }
                }
            }

            if (empty($countries)) {
                foreach ($util->getSupportedRegions() as $country) {
                    $countries[$country] = $util->getCountryCodeForRegion($country);
                }
            }

            $countryChoices = array();

            foreach (Languages::getNames() as $region => $name) {
                if (false === isset($countries[$region])) {
                    continue;
                }

                $countryChoices[sprintf('%s (+%s)', $name, $countries[$region])] = $region;
            }

            $transformerChoices = array_values($countryChoices);

            $countryOptions = $numberOptions = array(
                'error_bubbling' => true,
                'required' => $options['required'],
                'disabled' => $options['disabled'],
                'translation_domain' => $options['translation_domain'],
            );

            if (method_exists('Symfony\\Component\\Form\\AbstractType', 'getBlockPrefix')) {
                $choiceType = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\ChoiceType';
                $textType = 'Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType';
                $countryOptions['choice_translation_domain'] = false;

                // To be removed when dependency on Symfony Form is bumped to 3.1.
                if (!in_array(
                    'Symfony\\Component\\Form\\DataTransformerInterface',
                    class_implements('Symfony\\Component\\Form\\Extension\\Core\\Type\\TextType')
                )) {
                    $countryOptions['choices_as_values'] = true;
                }
            } else {
                // To be removed when dependency on Symfony Form is bumped to 2.7.
                $choiceType = 'choice';
                $textType = 'text';
                $countryChoices = array_flip($countryChoices);
            }

            $countryOptions['required'] = true;
            $countryOptions['choices'] = $countryChoices;
            $countryOptions['preferred_choices'] = $options['preferred_country_choices'];

            if ($options['country_placeholder']) {
                $countryOptions['placeholder'] = $options['country_placeholder'];
            }

            $builder
                ->add('country', $choiceType, $countryOptions)
                ->add('number', $textType, $numberOptions)
                ->addViewTransformer(new PhoneNumberToArrayTransformer($transformerChoices));
        } else {
            $builder->addViewTransformer(
                new PhoneNumberToStringTransformer($options['default_region'], $options['format'])
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['type'] = 'tel';
        $view->vars['widget'] = $options['widget'];
    }

    public function setDefaultOptions(OptionsResolver $resolver): void
    {
        $this->configureOptions($resolver);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array(
                'widget' => self::WIDGET_SINGLE_TEXT,
                'compound' => function (Options $options) {
                    return PhoneNumberType::WIDGET_SINGLE_TEXT !== $options['widget'];
                },
                'default_region' => PhoneNumberUtil::UNKNOWN_REGION,
                'format' => PhoneNumberFormat::INTERNATIONAL,
                'invalid_message' => 'This value is not a valid phone number.',
                'by_reference' => false,
                'error_bubbling' => false,
                'country_choices' => array(),
                'country_placeholder' => false,
                'preferred_country_choices' => array(),
            )
        );

        if (method_exists($resolver, 'setDefault')) {
            $resolver->setAllowedValues(
                'widget',
                array(
                    self::WIDGET_SINGLE_TEXT,
                    self::WIDGET_COUNTRY_CHOICE,
                )
            );
        } else {
            // To be removed when dependency on Symfony OptionsResolver is bumped to 2.6.
            $resolver->setAllowedValues(
                array(
                    'widget' => array(
                        self::WIDGET_SINGLE_TEXT,
                        self::WIDGET_COUNTRY_CHOICE,
                    ),
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'phone_number';
    }
}
