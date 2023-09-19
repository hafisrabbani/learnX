<?php

namespace App\Services\ForumThreads;

use LaravelEasyRepository\Service;
use App\Repositories\ForumThreads\ForumThreadsRepository;


class ForumThreadsServiceImplement extends Service implements ForumThreadsService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(ForumThreadsRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  // Define your custom methods :)
}
