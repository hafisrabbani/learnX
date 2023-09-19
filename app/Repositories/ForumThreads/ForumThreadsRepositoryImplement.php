<?php

namespace App\Repositories\ForumThreads;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ForumThread;

class ForumThreadsRepositoryImplement extends Eloquent implements ForumThreadsRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(ForumThread $model)
    {
        $this->model = $model;
    }

    public function reply($data)
    {
        return $this->model->create($data);
    }
}
