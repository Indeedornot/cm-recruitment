<?php

namespace App\Contract\PhoneNumber\Form\DataTransformer;


use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Phone number to string transformer.
 */
class PhoneNumberToStringTransformer implements DataTransformerInterface
{
    /**
     * Default region code.
     */
    private string $defaultRegion;

    /**
     * Display format.
     */
    private int $format;

    /**
     * Constructor.
     *
     * @param string $defaultRegion Default region code.
     * @param int $format Display format.
     */
    public function __construct(
        string $defaultRegion = PhoneNumberUtil::UNKNOWN_REGION,
        int $format = PhoneNumberFormat::INTERNATIONAL
    ) {
        $this->defaultRegion = $defaultRegion;
        $this->format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value): mixed
    {
        if (null === $value) {
            return '';
        } elseif (false === $value instanceof PhoneNumber) {
            throw new TransformationFailedException('Expected a ' . PhoneNumber::class);
        }

        $util = PhoneNumberUtil::getInstance();

        if (PhoneNumberFormat::NATIONAL === $this->format) {
            return $util->formatOutOfCountryCallingNumber($value, $this->defaultRegion);
        }

        return $util->format($value, $this->format);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value): ?PhoneNumber
    {
        if (!$value && $value !== '0') {
            return null;
        }

        $util = PhoneNumberUtil::getInstance();

        try {
            return $util->parse($value, $this->defaultRegion);
        } catch (NumberParseException $e) {
            throw new TransformationFailedException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
