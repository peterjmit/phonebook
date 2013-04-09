<?php

namespace Validation;

class ContactValidator
{
    public function validate($data)
    {
        $data = $this->sanitize($data);

        $this->assertExistsInArray('first_name', $data); // required
        $this->assertExistsInArray('last_name', $data); // required
        $this->assertExistsInArray('numbers', $data); // required

        foreach ($data['numbers'] as $number) {
            $this->assertExistsInArray('number', $number);
        }

        return $data;
    }

    public function sanitize($unsafeData)
    {
        $data = array('numbers' => array());

        if (isset($unsafeData['id'])) {
            $data['id'] = $this->sanitizeInt($unsafeData['id']);
        }

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
        return filter_var($variable, FILTER_SANITIZE_NUMBER_INT);
    }

    private function sanitizeString($string)
    {
        return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    private function assertExistsInArray($key, $array)
    {
        if (array_key_exists($key, $array) && isset($array[$key]) && !empty($array[$key])) {
            return;
        }

        throw new \InvalidArgumentException(sprintf('Expected %s to not be empty', $key));
    }
}
