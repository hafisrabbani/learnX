<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository
{
    public function getAll(): array;
    public function getById(int $id): array;
    public function createUser(array $data): bool;
    public function updateUser(int $id, array $data): bool;
    public function deleteUser(int $id): bool;
    public function getBy(array $conditions): array;
}
