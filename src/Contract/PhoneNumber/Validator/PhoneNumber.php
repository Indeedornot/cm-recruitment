<?php

namespace App\Contract\PhoneNumber\Validator;

use libphonenumber\PhoneNumberUtil;
use Symfony\Component\Validator\Constraint;

/**
 * @see https://github.com/Junker/phone-number-validator
 */
#[\Attribute]
class PhoneNumber extends Constraint
{
    public const string ANY = 'any';
    public const string FIXED_LINE = 'fixed_line';
    public const string MOBILE = 'mobile';
    public const string PAGER = 'pager';
    public const string PERSONAL_NUMBER = 'personal_number';
    public const string PREMIUM_RATE = 'premium_rate';
    public const string SHARED_COST = 'shared_cost';
    public const string TOLL_FREE = 'toll_free';
    public const string UAN = 'uan';
    public const string VOIP = 'voip';
    public const string VOICEMAIL = 'voicemail';

    public const string INVALID_PHONE_NUMBER_ERROR = 'ca23f4ca-38f4-4325-9bcc-eb570a4abe7f';

    protected static array $errorNames = array(
        self::INVALID_PHONE_NUMBER_ERROR => 'INVALID_PHONE_NUMBER_ERROR',
    );

    public mixed $message = null;
    public string $type = self::ANY;
    public string $defaultRegion = PhoneNumberUtil::UNKNOWN_REGION;

    public function getType(): string
    {
        return match ($this->type) {
            self::FIXED_LINE,
            self::MOBILE,
            self::PAGER,
            self::PERSONAL_NUMBER,
            self::PREMIUM_RATE,
            self::SHARED_COST,
            self::TOLL_FREE,
            self::UAN,
            self::VOIP,
            self::VOICEMAIL => $this->type,
            default => self::ANY,
        };
    }

    public function getMessage()
    {
        if (null !== $this->message) {
            return $this->message;
        }

        return match ($this->type) {
            self::FIXED_LINE => 'This value is not a valid fixed-line number.',
            self::MOBILE => 'This value is not a valid mobile number.',
            self::PAGER => 'This value is not a valid pager number.',
            self::PERSONAL_NUMBER => 'This value is not a valid personal number.',
            self::PREMIUM_RATE => 'This value is not a valid premium-rate number.',
            self::SHARED_COST => 'This value is not a valid shared-cost number.',
            self::TOLL_FREE => 'This value is not a valid toll-free number.',
            self::UAN => 'This value is not a valid UAN.',
            self::VOIP => 'This value is not a valid VoIP number.',
            self::VOICEMAIL => 'This value is not a valid voicemail access number.',
            default => 'This value is not a valid phone number.',
        };
    }
}
