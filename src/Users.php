<?php

namespace Max\HexletSlimExample;

class Users
{    
    private string $file = __DIR__ . '/data/users.json';

    public function save(array $user): void
    {
        $data = $this->getUsers();
        $id = $data['idCounter'];
        $user['id'] = $id;
        $data[$id] = $user;
        $data['idCounter']++;
        file_put_contents($this->file, json_encode($data));
    }

    public function getUsers(): array
    {
        $data = file_get_contents($this->file);
        $users = json_decode($data, true);
        return $users ?? [];
    }

    public function getUserById(int $id): ?array
    {
        $users = $this->getUsers();
        if (!isset($users[$id])) {
            return null;
        }
        return $users[$id];
    }

    public function editUser(int $id, string $nickname, string $email): void
    {
        $data = $this->getUsers();
        if (isset($data[$id])) {
            if ($nickname) {
                $data[$id]['nickname'] = $nickname;
            }
            if ($email) {
            $data[$id]['email'] = $email;
            }
        }
        file_put_contents($this->file, json_encode($data));
    }

    public function deleteUser(int $id): bool
    {
        $users = $this->getUsers();
        if (isset($users[$id])) {
            unset($users[$id]);
            file_put_contents($this->file, json_encode($users));
            return true;
        }
        return false;
    }
}