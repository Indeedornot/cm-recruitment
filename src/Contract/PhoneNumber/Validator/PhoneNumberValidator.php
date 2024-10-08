<?php

/**
 * @see https://github.com/Junker/phone-number-validator
 */

namespace App\Contract\PhoneNumber\Validator;

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber as PhoneNumberObject;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberType;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PhoneNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if (!($constraint instanceof PhoneNumber)) {
            throw new UnexpectedTypeException($constraint, PhoneNumber::class);
        }

        $phoneUtil = PhoneNumberUtil::getInstance();

        if (false === $value instanceof PhoneNumberObject) {
            $value = (string)$value;

            try {
                $phoneNumber = $phoneUtil->parse($value, $constraint->defaultRegion);
            } catch (NumberParseException $e) {
                $this->addViolation($value, $constraint);

                return;
            }
        } else {
            $phoneNumber = $value;
            $value = $phoneUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
        }

        if (false === $phoneUtil->isValidNumber($phoneNumber)) {
            $this->addViolation($value, $constraint);

            return;
        }

        $validTypes = match ($constraint->getType()) {
            PhoneNumber::FIXED_LINE => [PhoneNumberType::FIXED_LINE, PhoneNumberType::FIXED_LINE_OR_MOBILE],
            PhoneNumber::MOBILE => [PhoneNumberType::MOBILE, PhoneNumberType::FIXED_LINE_OR_MOBILE],
            PhoneNumber::PAGER => [PhoneNumberType::PAGER],
            PhoneNumber::PERSONAL_NUMBER => [PhoneNumberType::PERSONAL_NUMBER],
            PhoneNumber::PREMIUM_RATE => [PhoneNumberType::PREMIUM_RATE],
            PhoneNumber::SHARED_COST => [PhoneNumberType::SHARED_COST],
            PhoneNumber::TOLL_FREE => [PhoneNumberType::TOLL_FREE],
            PhoneNumber::UAN => [PhoneNumberType::UAN],
            PhoneNumber::VOIP => [PhoneNumberType::VOIP],
            PhoneNumber::VOICEMAIL => [PhoneNumberType::VOICEMAIL],
            default => [],
        };

        if (count($validTypes)) {
            $type = $phoneUtil->getNumberType($phoneNumber);

            if (false === in_array($type, $validTypes)) {
                $this->addViolation($value, $constraint);
            }
        }
    }

    /**
     * Add a violation.
     *
     * @param mixed $value The value that should be validated.
     * @param Constraint $constraint The constraint for the validation.
     */
    private function addViolation(mixed $value, Constraint $constraint): void
    {
        if (!($constraint instanceof PhoneNumber)) {
            throw new UnexpectedTypeException($constraint, PhoneNumber::class);
        }

        $this->context->buildViolation($constraint->getMessage())
            ->setParameter('{{ type }}', $constraint->getType())
            ->setParameter('{{ value }}', $this->formatValue($value))
            ->setCode(PhoneNumber::INVALID_PHONE_NUMBER_ERROR)
            ->addViolation();
    }
}
