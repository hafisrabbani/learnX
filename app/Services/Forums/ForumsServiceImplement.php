<?php

namespace App\Services\Forums;

use LaravelEasyRepository\Service;
use App\Repositories\Forums\ForumsRepository;

class ForumsServiceImplement extends Service implements ForumsService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(ForumsRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
}
