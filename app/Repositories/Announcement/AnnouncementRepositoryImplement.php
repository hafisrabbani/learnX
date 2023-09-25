<?php

namespace App\Repositories\Announcement;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Announcement;

class AnnouncementRepositoryImplement extends Eloquent implements AnnouncementRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Announcement $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
