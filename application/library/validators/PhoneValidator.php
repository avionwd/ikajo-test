<?php
/**
 * PhoneValidator file
 *
 * @author Oleksandr Muzychenko <avionwd@gmail.com>
 */

namespace app\library\validators;
use libphonenumber\PhoneNumberUtil;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

/**
 * Class PhoneValidator
 *
 * @package app\library\validators
 */
class PhoneValidator extends Validator
{
    /**
     * @param \Phalcon\Validation $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        if (!preg_match('/\+\d+/', $value)) {
            $validation->appendMessage(new Message('Use only phones in international format', $attribute));
            return false;
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $swissNumberProto = $phoneUtil->parse($value, null);
            if (!$phoneUtil->isValidNumber($swissNumberProto)) {
                $validation->appendMessage(new Message('Phone number is invalid', $attribute));
                return false;
            }
        } catch (\libphonenumber\NumberParseException $e) {
            $validation->appendMessage(new Message('Use only phones in international format', $attribute));
            return false;
        }

        return true;
    }
}