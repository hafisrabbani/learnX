<?php

namespace App\Services\Tugas;

use LaravelEasyRepository\Service;
use App\Repositories\Tugas\TugasRepository;

class TugasServiceImplement extends Service implements TugasService
{

  /**
   * don't change $this->mainRepository variable name
   * because used in extends service class
   */
  protected $mainRepository;

  public function __construct(TugasRepository $mainRepository)
  {
    $this->mainRepository = $mainRepository;
  }

  // Define your custom methods :)
  public function create($data)
  {
    $request = [
      'judul_tugas' => $data['judul_tugas'],
      'id_matkul' => $data['id_matkul'],
      'deskripsi' => $data['deskripsi'],
      'deadline' => $data['deadline'],
      'attachment' => $data['files']
    ];
    return $this->mainRepository->create($request)->exists;
  }

  public function getByMatkul($id)
  {
    return $this->mainRepository->getByMatkul($id);
  }

  public function delete($id)
  {
    return $this->mainRepository->delete($id);
  }
}
