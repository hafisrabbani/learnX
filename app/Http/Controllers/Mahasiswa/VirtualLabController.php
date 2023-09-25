<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VirtualLabController extends Controller
{
    public function allVirtualLab(): array
    {
        return [
            'Kanban-Board' => [
                'title' => 'Kanban Board',
                'url' => route('mahasiswa.virtual-lab.kanban-board'),
                'icon' => 'fas fa-clipboard-list',
            ],
            'Operating-System' => [
                'title' => 'Operating System Simulation',
                'url' => route('mahasiswa.virtual-lab.operating-system-simulate'),
                'icon' => 'fab fa-ubuntu',
            ],
            'API-Tester' => [
                'title' => 'API Tester',
                'url' => route('mahasiswa.virtual-lab.api-tester'),
                'icon' => 'fas fa-network-wired',
            ],
            'PHP-Compiler' => [
                'title' => 'PHP Compiler',
                'url' => route('mahasiswa.virtual-lab.php-compiler'),
                'icon' => 'fab fa-php',
            ],
            'Python-Compiler' => [
                'title' => 'Python Compiler',
                'url' => route('mahasiswa.virtual-lab.python-compiler'),
                'icon' => 'fab fa-python',
            ],
        ];
    }

    public function index()
    {
        return view('mahasiswa.virtual-lab.index', [
            'virtualLabs' => $this->allVirtualLab(),
        ]);
    }

    public function circuitsSimulate()
    {
        return view('mahasiswa.virtual-lab.circuit');
    }

    public function operatingSystemSimulate()
    {
        return view('mahasiswa.virtual-lab.terminal');
    }

    public function capacitorSimulate()
    {
        return view('mahasiswa.virtual-lab.capacitor');
    }

    public function geometricOpticsSimulate()
    {
        return view('mahasiswa.virtual-lab.geometric-optics');
    }

    public function apiTester()
    {
        return view('mahasiswa.virtual-lab.api-tester');
    }

    public function phpCompiler()
    {
        return view('mahasiswa.virtual-lab.php-compiler');
    }

    public function kanbanBoard()
    {
        return view('mahasiswa.virtual-lab.kanban-board');
    }

    public function pythonCompiler()
    {
        return view('mahasiswa.virtual-lab.python-compiler');
    }
}
