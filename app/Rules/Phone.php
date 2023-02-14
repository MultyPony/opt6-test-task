<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Phone implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) : bool
    {
        $result = false;
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $numberProto = $phoneUtil->parse($value, 'RU');
            $result = $phoneUtil->isValidNumber($numberProto);
        } catch (\libphonenumber\NumberParseException $e) {

        }
        return $result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Неверный формат номера телефона.';
    }
}
