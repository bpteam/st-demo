<?php

namespace App\Repository;

use App\Entity\User;

interface UserRepository
{
    public function findByNickname(string $nickname): ?User;
    public function save(User $user): void;
}