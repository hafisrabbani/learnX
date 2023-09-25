<?php

namespace App\Services\Absence;

use LaravelEasyRepository\Service;
use App\Repositories\Absence\AbsenceRepository;

class AbsenceServiceImplement extends Service implements AbsenceService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(AbsenceRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function create($data)
  {
    return $this->mainRepository->create($data);
  }

  public function createDetail($data)
  {
    return $this->mainRepository->createDetail($data);
  }
}
