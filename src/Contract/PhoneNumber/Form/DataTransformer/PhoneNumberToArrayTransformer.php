<?php

namespace App\Contract\PhoneNumber\Form\DataTransformer;


use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Override;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Phone number to array transformer.
 */
class PhoneNumberToArrayTransformer implements DataTransformerInterface
{
    private array $countryChoices;

    public function __construct(array $countryChoices)
    {
        $this->countryChoices = $countryChoices;
    }

    public function transform($value): array
    {
        if (null === $value) {
            return array('country' => '', 'number' => '');
        } elseif (false === $value instanceof PhoneNumber) {
            throw new TransformationFailedException('Expected a \libphonenumber\PhoneNumber.');
        }

        $util = PhoneNumberUtil::getInstance();

        if (false === in_array($util->getRegionCodeForNumber($value), $this->countryChoices)) {
            throw new TransformationFailedException('Invalid country.');
        }

        return array(
            'country' => $util->getRegionCodeForNumber($value),
            'number' => $util->format($value, PhoneNumberFormat::NATIONAL),
        );
    }

    public function reverseTransform($value): ?PhoneNumber
    {
        if (!$value) {
            return null;
        }

        if (!is_array($value)) {
            throw new TransformationFailedException('Expected an array.');
        }

        if ('' === trim($value['number'])) {
            return null;
        }

        $util = PhoneNumberUtil::getInstance();

        try {
            $phoneNumber = $util->parse($value['number'], $value['country']);
        } catch (NumberParseException $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }

        if (false === in_array($util->getRegionCodeForNumber($phoneNumber), $this->countryChoices)) {
            throw new TransformationFailedException('Invalid country.');
        }

        return $phoneNumber;
    }
}
