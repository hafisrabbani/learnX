<?php

namespace App\Services\Submission;

use LaravelEasyRepository\Service;
use App\Repositories\Submission\SubmissionRepository;

class SubmissionServiceImplement extends Service implements SubmissionService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(SubmissionRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getByTugas($id_tugas)
  {
    return $this->mainRepository->getByTugas($id_tugas);
  }
}
