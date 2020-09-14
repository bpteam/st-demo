<?php

namespace App\Entity;

use JsonSerializable;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, JsonSerializable
{

    private UuidInterface $id;
    private string $firstname;
    private string $lastname;
    private string $nickname;
    private string $password;
    private int $age;

    public function __construct(
        UuidInterface $id,
        string $firstname,
        string $lastname,
        string $nickname,
        string $password,
        int $age
    ) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->nickname = $nickname;
        $this->password = $password;
        $this->age = $age;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        // No need if use bcrypt
    }

    public function getUsername()
    {
        return $this->nickname;
    }

    public function getNickname()
    {
        return $this->nickname;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id->jsonSerialize(),
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'nickname' => $this->nickname,
            'password' => $this->password,
            'age' => $this->age,
        ];
    }
}