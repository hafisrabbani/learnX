<?php

namespace App\Services\Announcement;

use LaravelEasyRepository\Service;
use App\Repositories\Announcement\AnnouncementRepository;

class AnnouncementServiceImplement extends Service implements AnnouncementService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(AnnouncementRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
