<section>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="fw-bold text-center">{{ $totalMatkul }}</h3>
                            <p class="text-muted text-center fw-bold">Mata Kuliah</p>
                        </div>
                        <div class="col-4">
                            <div class="bg-primary text-center text-white py-3 rounded">
                                <i class="bi bi-book" style="font-size: 2rem;font-weight: bold;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="fw-bold text-center">{{ $totalMateri }}</h3>
                            <p class="text-muted text-center fw-bold">Materi</p>
                        </div>
                        <div class="col-4">
                            <div class="bg-success text-center text-white py-3 rounded">
                                <i class="fas fa-file-pdf" style="font-size: 2rem;font-weight: bold;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h3 class="fw-bold text-center">{{ $totalTugas }}</h3>
                            <p class="text-muted text-center fw-bold">Tugas</p>
                        </div>
                        <div class="col-4">
                            <div class="bg-warning text-center text-white py-3 rounded">
                                <i class="bi bi-file-earmark-text" style="font-size: 2rem;font-weight: bold;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section>
    <div class="row align-items-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold">Rekomendasi :</h5>
                    <p class="text-secondary
                    ">{!! nl2br($personalisasi) !!}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <canvas id="progress-belajar"></canvas>
                </div>
            </div>
        </div>
    </div>

</section>

<script src=" https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js "></script>
<script>
    const data = {
        labels: [
            @foreach ($matkul as $m)
                '{{ $m->matkul->nama_mk }}',
            @endforeach
        ],
        datasets: [{
            label: 'Progress Belajarmu',
            data: [
                @foreach ($matkul as $m)
                    '{{ $m->point }}',
                @endforeach
            ],
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(255, 205, 86)',
                'rgb(54, 162, 235)',
                'rgb(75, 192, 192)',
            ]
        }],
    }
    const configs = {
        type: 'radar',
        data: data,
        options: {
            elements: {
                line: {
                    borderWidth: 2
                }
            }
        },
    }

    var myChart = new Chart(
        document.getElementById('progress-belajar'),
        configs
    );
</script>
