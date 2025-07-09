<?php

namespace Max\HexletSlimExample;

class CarValidator
{
    public function validate(array $car): array
    {
        $errors = []; 

        if (empty($car['make'])) {
            $errors['make'] = "Make can't be empty";
        }
        if (empty($car['model'])) {
            $errors['model'] = "Model can't be empty";
        }
        return $errors;
    }
}