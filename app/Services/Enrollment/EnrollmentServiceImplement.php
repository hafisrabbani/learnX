<?php

namespace App\Services\Enrollment;

use LaravelEasyRepository\Service;
use App\Repositories\Enrollment\EnrollmentRepository;

class EnrollmentServiceImplement extends Service implements EnrollmentService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(EnrollmentRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  public function getMatkulByMahasiswa($id_mahasiswa)
  {
    $enrollment = $this->mainRepository->getByMahasiswa($id_mahasiswa);
  }
}
