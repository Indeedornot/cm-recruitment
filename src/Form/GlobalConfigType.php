<?php

namespace App\Form;

use App\Entity\GlobalConfig;
use App\Repository\GlobalConfigRepository;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToStringTransformer;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\ReversedTransformer;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GlobalConfigType extends AbstractType
{
    public function __construct(
        private readonly GlobalConfigRepository $repository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $configs = $this->repository->findAll();

        foreach ($configs as $config) {
            $data = $config->getValue();
            $builder->add('config_' . $config->getKey(), $config->getFormType(), array_merge([
                'label' => $config->getLabel(),
                'data' => $data,
                'constraints' => $config->getConstraints(),
                'mapped' => false,
            ], $config->getFormOptions()));
            if ($config->getFormType() === DateTimeType::class) {
                $builder->get('config_' . $config->getKey())->addModelTransformer(new ReversedTransformer(
                    new DateTimeToStringTransformer(null, null, 'Y-m-d H:i:s'),
                ));
            }
        }

        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) use ($configs) {
            $form = $event->getForm();

            foreach ($configs as $config) {
                $value = $form->get('config_' . $config->getKey())->getData();
                $config->setValue($value);
                $this->repository->save($config, true);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
