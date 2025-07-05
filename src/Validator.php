<?php

namespace Max\HexletSlimExample;

class Validator
{
    public function validate(array $user): array
    {
        $errors = [];

        if (empty($user['nickname'])) {
            $errors['nickname'] = "Nickname can't be blank";
        }

        if (strlen($user['nickname']) < 4) {
            $errors['nickname'] = "Nickname must be grater then 4 characters";
        }

        if (empty($user['email'])) {
            $errors['email'] = "Email can't be blank";
        }

        return $errors;
    }
}