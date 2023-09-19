<?php

namespace App\Services\Matkul;

use App\Models\User;
use LaravelEasyRepository\Service;
use App\Repositories\Matkul\MatkulRepository;
use Illuminate\Support\Facades\Auth;

class MatkulServiceImplement extends Service implements MatkulService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(MatkulRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    // Define your custom methods :)
    function getByDosen(){
      $dosenId = Auth::user()->id;
      return $this->mainRepository->getByDosen($dosenId);
    }

}
