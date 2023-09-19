@extends('layouts.main')

@section('_title', 'Quiz')
@section('page-heading', 'Quiz')
@section('css')
<style>
    .box-answer {
        border: 1px solid #ccc !important;
        margin: 3px;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .box-question {
        border: 1px solid #bbbbbb !important;
        margin: 3px;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: var(--bs-blue);
        color: #ffffff;
        font-weight: bold;
    }

    .box-answer:hover {
        background-color: #ccc !important;
    }

    .box-answer:selected {
        background-color: #ccc !important;
    }

    .card-quiz {
        border: 1px solid #bbbbbb !important;
        margin: 3px;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }
</style>
@endsection
@section('page-content')
<section class="row justify-content-center">
    <div class="col-12">
        <div class="row">
            <div class="col-md-{{ ($analytic !== null) ? '8' : '12' }}">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Perhatian!</strong>
                            Quiz ini dibuat oleh sistem tidak akan mempengaruhi nilai anda.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <h4 class="mb-3 text-capitalize">Quiz : {{ $materi->judul_materi }}</h4>
                        @if($getAllQuizWithAnswer == null)
                        <form id="form-quiz">
                            @csrf
                            <input type="hidden" name="materi_id" value="{{ $materi->id }}">
                            @foreach ($quiz as $q)
                            <div class="card card-quiz">
                                <div class="card-header box-question py-4 text-white">
                                    <h6 class="mb-0 text-capitalize text-white">{{ $loop->iteration }}.{{ $q->question
                                        }}</h6>
                                </div>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item box-answer text-muted"><input type="radio"
                                            name="quiz[{{ $q->id }}]" value="A"> {{ $q->option_a }}</li>
                                    <li class="list-group-item box-answer text-muted"><input type="radio"
                                            name="quiz[{{ $q->id }}]" value="B"> {{ $q->option_b }}</li>
                                    <li class="list-group-item box-answer text-muted"><input type="radio"
                                            name="quiz[{{ $q->id }}]" value="C"> {{ $q->option_c }}</li>
                                    <li class="list-group-item box-answer text-muted"><input type="radio"
                                            name="quiz[{{ $q->id }}]" value="D"> {{ $q->option_d }}</li>
                                </ul>
                            </div>
                            @endforeach
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                        @else
                        <h5 class="text-end">Score : {{ $nilai }}</h5>
                        @foreach ($getAllQuizWithAnswer as $q)
                        <div class="card card-quiz">
                            <div class="card-body">
                                <h6>{{ $q->quiz->question }}</h6>
                                <span class="badge badge text-bg-primary">Jawaban Kamu : {{ $q->answer }}</span>
                                {!!($q->answer
                                == $q->quiz->true_answer) ? '<span class="badge text-bg-success">Benar</span>' : '<span
                                    class="badge text-bg-danger">Salah</span>'!!}
                                <p class="text-muted">
                                    @php
                                    $trueAnswer = 'option_'.strtolower($q->quiz->true_answer);
                                    @endphp
                                    Jawaban Benar : <br><span class="text-primary">{{ $q->quiz->$trueAnswer }}</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                @if($analytic !== null)
                <div class="card card-quiz">
                    <div class="card-body">
                        <h6>Rekomendasi Dari Sistem</h6>
                        <p id="link-replacement">
                            {!! nl2br($analytic) !!}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
@push('js')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    $(document).ready(function () {
        $('#link-replacement').html(function (_, html) {
            var url = html.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
            // url = url.replace(/<br>/g, ''); // Menggunakan regex dengan /g untuk mengganti semua <br> occurrences
            return url;
        });
    });


    $('#form-quiz').on('submit', function () {

        var data = $(this).serialize();
        var url = "{{ route('mahasiswa.personalisasi.storeQuiz', $materi->id) }}";
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (data) {
                swal({
                    icon: 'success',
                    title: 'Berhasil',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then(function () {
                    window.reload();
                });
            },
            error: function (data) {
                swal({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.responseJSON.message,
                });
            },
            beforeSend: function () {
                swal({
                    icon: 'info',
                    title: 'Loading...',
                    text: 'Mohon Tunggu Sebentar',
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });
            }
        });

        return false;

    });


</script>
@endpush