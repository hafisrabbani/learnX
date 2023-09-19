<?php

namespace App\Http\Controllers\mahasiswa;

use App\Http\Controllers\Controller;
use App\Repositories\Badge\BadgeRepository;
use Illuminate\Http\Request;

class BadgeController extends Controller
{
    protected $badgeRepository;
    public function __construct(
        BadgeRepository $badgeRepository
    ) {
        $this->badgeRepository = $badgeRepository;
    }

    public function getBadge($matkul_id, $mahasiswa_id)
    {
        $badge = $this->badgeRepository->getByMatkul($matkul_id, $mahasiswa_id);
        return response()->json([
            'status' => true,
            'data' => $badge
        ]);
    }
}
