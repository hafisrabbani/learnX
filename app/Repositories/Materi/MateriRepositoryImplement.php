<?php

namespace App\Repositories\Materi;

use App\Models\Feedback;
use App\Models\MataKuliah;
use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MateriRepositoryImplement extends Eloquent implements MateriRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Materi $model)
    {
        $this->model = $model;
    }

    public function getByMatkul($id)
    {
        return MataKuliah::find($id)->materi()->get();
    }

    public function create($data)
    {
        // code buat simpen file ke directory (belum)
        return $this->model->create([
            'judul_materi' => $data['judul_materi'],
            'id_matkul' => $data['id_matkul'],
            'deskripsi' => $data['deskripsi'],
            'attachment' => $data['attachment']
        ]);
    }

    public function delete($id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function where(array $data)
    {
        return $this->model->where($data)->get();
    }

    public function storeFeedback(array $data)
    {
        return Feedback::create([
            'mahasiswa_id' => Auth::user()->id,
            'materi_id' => $data['materi_id'],
            'point' => $data['point'],
        ]);
    }

    public function whereFeedback($materi_id)
    {
        $mahasiswa_id = Auth::user()->id;
        return Feedback::where('mahasiswa_id', $mahasiswa_id)->where('materi_id', $materi_id)->first();
    }

    public function updateFeedback(array $data)
    {
        return Feedback::where('materi_id', $data['materi_id'])->where('mahasiswa_id', Auth::user()->id)->update([
            'point' => $data['point'],
        ]);
    }
}
