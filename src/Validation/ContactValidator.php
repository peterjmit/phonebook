<?php

namespace Validation;

class ContactValidator
{
    public function validate($data)
    {
        $this->assertExistsInArray('first_name', $data);
        $this->assertExistsInArray('last_name', $data);
        $this->assertExistsInArray('numbers', $data);

        foreach ($data['numbers'] as $number) {
            $this->assertExistsInArray('number', $number);
            $this->assertTelephone($number['number']);
        }

        return $this->sanitize($data);
    }

    public function sanitize($unsafeData)
    {
        $data = array('numbers' => array());

        if (isset($unsafeData['id'])) {
            $data['id'] = $this->sanitizeInt($unsafeData['id']);
        }

        // Dont really need to do anything with these,
        // output is escaped on the front end and double escaping
        // leads to issues
        $data['first_name'] = $unsafeData['first_name'];
        $data['last_name'] = $unsafeData['last_name'];

        foreach ($unsafeData['numbers'] as $number) {
            $numberData = array();
            if (isset($number['id'])) {
                $numberData['id'] = $this->sanitizeInt($number['id']);
            }

            $numberData['number'] = $this->sanitizeInt($number['number']);

            $data['numbers'][] = $numberData;
        }

        return $data;
    }

    private function sanitizeInt($variable)
    {
        return filter_var(str_replace(' ', '', $variable), FILTER_SANITIZE_NUMBER_INT);
    }

    private function assertTelephone($string)
    {
        if (preg_match('/\d[+-]\d/', $string) === 1) {
            return;
        }

        throw new ValidationException(sprintf('%s is not a valid number', $string));
    }

    private function assertExistsInArray($key, $array)
    {
        if (array_key_exists($key, $array) && isset($array[$key]) && !empty($array[$key])) {
            return;
        }

        throw new ValidationException(sprintf('Expected %s to not be empty', $key));
    }
}
