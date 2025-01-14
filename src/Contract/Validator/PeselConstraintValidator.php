<?php

namespace App\Contract\Validator;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PeselConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PeselConstraint) {
            throw new UnexpectedTypeException($constraint, PeselConstraint::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value) && !is_int($value)) {
            throw new UnexpectedValueException($value, 'string|int');
        }

        $pesel = (string)$value;
        if (!self::validatePesel($pesel, $constraint->options)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }

    private const array GENDER_DICT = [
        'male' => 'm',
        'man' => 'm',
        'm' => 'm',
        'female' => 'f',
        'woman' => 'f',
        'f' => 'f',
        'w' => 'w',
        'other' => 'o',
        'o' => 'o'
    ];

    public static function validatePesel(string $pesel, array $optionalArgs = []): bool
    {
        $dateOfBirth = $optionalArgs['dateOfBirth'] ?? null;
        $gender = $optionalArgs['gender'] ?? null;
        $age = $optionalArgs['age'] ?? null;

        self::checkTypes($pesel, $dateOfBirth, $gender, $age);

        if (!preg_match('/^\d{11}$/', $pesel)) {
            return false;
        }

        $datePart = substr($pesel, 0, 6);
        $genderPart = $pesel[9];

        return self::validateDatePart($datePart) &&
            self::datesEqual($datePart, $dateOfBirth) &&
            self::genderEqual($genderPart, $gender) &&
            self::validateCheckSum($pesel) &&
            self::ageMatches($datePart, $age);
    }

    private static function ageMatches($datePart, $age): bool
    {
        if ($age === null) {
            return true;
        }

        $yearPart = substr($datePart, 0, 2);
        $monthPart = substr($datePart, 2, 2);
        $dayPart = substr($datePart, 4, 2);

        $yearBeginning = self::getYearBeginning($yearPart, $monthPart);
        $decodedMonth = self::getDecodedMonth($monthPart);

        $birthDate = DateTime::createFromFormat('Y-m-d',
            $yearBeginning . $yearPart . '-' . $decodedMonth . '-' . $dayPart);
        $today = new DateTime();

        $calculatedAge = $today->diff($birthDate)->y;

        return $calculatedAge === $age;
    }

    private static function validateDatePart($datePart): bool
    {
        $yearPart = substr($datePart, 0, 2);
        $monthPart = substr($datePart, 2, 2);
        $dayPart = substr($datePart, 4, 2);

        if (!preg_match('/^[0-9][0-9]([02468][1-9]|[13579][0-2])$/', $yearPart . $monthPart)) {
            return false;
        }

        $yearBeginning = self::getYearBeginning($yearPart, $monthPart);
        $decodedMonth = self::getDecodedMonth($monthPart);
        $fullYear = $yearBeginning . $yearPart;

        return checkdate($decodedMonth, intval($dayPart), intval($fullYear));
    }

    private static function datesEqual($datePart, $dateOfBirth): bool
    {
        if (!$dateOfBirth) {
            return true;
        }

        $yearPart = substr($datePart, 0, 2);
        $monthPart = substr($datePart, 2, 2);
        $dayPart = substr($datePart, 4, 2);

        $yearBeginning = self::getYearBeginning($yearPart, $monthPart);
        $decodedMonth = self::getDecodedMonth($monthPart);

        $peselDate = DateTime::createFromFormat('Y-m-d',
            $yearBeginning . $yearPart . '-' . $decodedMonth . '-' . $dayPart);

        if ($dateOfBirth instanceof DateTime) {
            return $peselDate->format('Y-m-d') === $dateOfBirth->format('Y-m-d');
        }

        return $peselDate->format('Y-m-d') === date('Y-m-d', strtotime($dateOfBirth));
    }

    private static function genderEqual($genderPart, $gender): bool
    {
        if (!$gender) {
            return true;
        }

        $lowerCaseGender = strtolower($gender);

        if (in_array($lowerCaseGender, ['f', 'female', 'w', 'woman'])) {
            $encodedGender = 0;
        } elseif (in_array($lowerCaseGender, ['m', 'male', 'man'])) {
            $encodedGender = 1;
        } elseif (in_array($lowerCaseGender, ['o', 'other'])) {
            return true;
        } else {
            throw new UnexpectedValueException($gender, "Gender parameter is invalid. " .
                "Allowed case insensitive values ('f', 'female', 'w', 'woman', 'm, 'man', 'male', 'o', 'other')");
        }

        return intval($genderPart) % 2 === $encodedGender;
    }

    private static function validateCheckSum($pesel): bool
    {
        $indexWeights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3, 1];
        $sum = 0;

        for ($i = 0; $i < 10; $i++) {
            $sum += intval($pesel[$i]) * $indexWeights[$i];
        }

        $shouldEqual = $sum % 10 ? 10 - $sum % 10 : 0;
        return intval($pesel[10]) === $shouldEqual;
    }

    private static function getYearBeginning($yearPart, $monthPart): string
    {
        $firstDigit = $monthPart[0];

        if ($firstDigit === '8' || $firstDigit === '9') {
            return '18';
        } elseif ($firstDigit === '0' || $firstDigit === '1') {
            return '19';
        } elseif ($firstDigit === '2' || $firstDigit === '3') {
            return '20';
        } elseif ($firstDigit === '4' || $firstDigit === '5') {
            return '21';
        } else {
            return '22';
        }
    }

    private static function getDecodedMonth($monthPart): string
    {
        return (intval($monthPart[0]) % 2) . $monthPart[1];
    }

    private static function checkTypes($pesel, $dateOfBirth, $gender, $age): void
    {
        if (!is_string($pesel)) {
            throw new UnexpectedValueException($pesel, 'string');
        }

        if ($dateOfBirth !== null &&
            !($dateOfBirth instanceof DateTime) &&
            !strtotime($dateOfBirth)) {
            throw new UnexpectedTypeException($dateOfBirth, "DateTime|string");
        }

        if ($gender !== null &&
            (!is_string($gender) ||
                !array_key_exists(strtolower($gender), self::GENDER_DICT))) {
            throw new UnexpectedTypeException(
                $gender,
                "Gender must be a string with one of the following values: " .
                implode(', ', array_keys(self::GENDER_DICT)));
        }

        if ($age !== null && !is_int($age)) {
            throw new UnexpectedTypeException($age, 'int');
        }
    }
}
