<?php

namespace App\Repository;

use App\Entity\User;
use Ramsey\Uuid\Uuid;
use RuntimeException;

class JsonUserRepository implements UserRepository
{
    private string $storagePath;

    public function __construct(string $storagePath = '/app/var/user-storage')
    {
        $this->storagePath = $storagePath;
        if(false === (file_exists($this->storagePath) || mkdir($this->storagePath, 0777, true))) {
            throw new RuntimeException('Can not create storage path ' . $this->storagePath);
        }
    }

    public function findByNickname(string $nickname): ?User
    {
        $filename = $this->getFileNameByNickname($nickname);
        if (file_exists($filename)) {
            $data = json_decode(file_get_contents($filename), true, 512,JSON_THROW_ON_ERROR);
            return new User(
                Uuid::fromString($data['id']),
                $data['firstname'],
                $data['lastname'],
                $data['nickname'],
                $data['password'],
                (int) $data['age']
            );
        }

        return null;
    }

    public function save(User $user): void
    {
        file_put_contents($this->getFileNameByNickname($user->getNickname()), json_encode($user, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR));
    }

    private function getFileNameByNickname(string $nickname): string
    {
        return $this->storagePath . DIRECTORY_SEPARATOR . $nickname;
    }
}