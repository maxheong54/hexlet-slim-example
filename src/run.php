<?php

namespace Max\HexletSlimExample;

require_once __DIR__ . '/../vendor/autoload.php';

$json = json_decode(
    '{"nickname":"milion","email":"dollars@gmail.xom"}',
    true
);

$jj = new Users();
$jj->deleteUser(2);

$users = $jj->getUsers();

dd($users);