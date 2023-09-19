<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepositoryImplement extends Eloquent implements UserRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getAll(): array
    {
        return $this->model->orderBy('role', 'asc')->get()->toArray();
    }

    public function createUser(array $data): bool
    {
        return $this->model->create($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getById(int $id): array
    {
        return $this->model->where('id', $id)->first()->toArray();
    }

    public function getBy(array $conditions): array
    {
        return $this->model->where($conditions)->get()->toArray();
    }
}
