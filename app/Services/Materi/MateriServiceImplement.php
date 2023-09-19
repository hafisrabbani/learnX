<?php

namespace App\Services\Materi;

use LaravelEasyRepository\Service;
use App\Repositories\Materi\MateriRepository;

class MateriServiceImplement extends Service implements MateriService
{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(MateriRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function create($data)
    {
        $request = [
            'judul_materi' => $data['judul_materi'],
            'id_matkul' => $data['id_matkul'],
            'deskripsi' => $data['deskripsi'],
            'attachment' => $data['files']
        ];
        return $this->mainRepository->create($request);
    }

    public function delete($id)
    {
        return $this->mainRepository->delete($id);
    }
}
