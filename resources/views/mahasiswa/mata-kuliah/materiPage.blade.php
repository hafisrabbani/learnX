@extends('layouts.main')

@section('_title', 'MATERI')
@section('page-heading', 'MATERI')

@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @foreach ($materis as $materi)
                    <div class="col-md-4">
                        <div class="card shadow p-1 mb-5 rounded" style="height: 15rem;">
                            <div class="card-body">

                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <h5 class="fw-bold">{{ $materi['judul_materi'] }}</h5>
                                        <p class="text-muted">
                                            {{ str_word_count($materi['deskripsi']) > 10 ? substr($materi['deskripsi'],
                                            0, 50) . '...' : $materi['deskripsi'] }}
                                        </p>
                                    </div>
                                    <div class="col-2">
                                        <div class="icon-container bg-{{ $randomIcon['color'] }} text-white float-end"
                                            style="width: 3rem; height: 3rem; border-radius: 5px; display: flex; justify-content: center; align-items: center;">
                                            <i class="{{ $randomIcon['icon'] }} icon" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ asset('storage/materi/' . $materi['attachment']) }}" target="_blank"
                                    class="btn btn-sm btn-warning"><i class="fas fa-file-download"></i></a>

                                @if (!$materi['status'])
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="showModal('create', {{ $materi->id }})"><i
                                        class="fas fa-star"></i></button>
                                @else
                                <button type="button" class="btn btn-sm btn-primary" disabled><i
                                        class="fas fa-star"></i></button>
                                @endif
                                <a href="{{ route('mahasiswa.personalisasi.quiz', $materi['id']) }}"
                                    class="btn btn-sm btn-info"><i class="fas fa-question"></i></a>
                                <a href="{{ route('utils.materi-audio', $materi['id']) }}"
                                    class="btn btn-sm btn-success"><i class="fas fa-play"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>


        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static"
            data-keyboard="false">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="form" method="POST">
                        <input type="hidden" value="" id="materi_id" name="materi_id">
                        <div class="modal-body d-flex justify-content-center">
                            <input type="range" name="point" id="point" min="1" max="5">
                            <label for="point" id="point-label">1</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</section>
@endsection

@push('js')
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {

        $('#point-label').html($('#point').val());

        $('#point').on('input', function () {
            $('#point-label').html($(this).val());
        });


        $('#form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function (data) {
                    swal({
                        title: "Success!",
                        text: data.message,
                        icon: "success",
                        button: "OK",
                    }).then((value) => {
                        window.location.reload();
                    });
                },
                error: function (xhr, status, error) {
                    swal({
                        title: "Error!",
                        text: error,
                        icon: "error",
                        button: "OK",
                    });
                }
            })

        })
    })

    function showModal(type, id = null) {
        if (type == 'create') {
            $('#modal-title').html('Tambah  Feedback');
            $('#form').attr('action', '{{ route('mahasiswa.materi.storeFeedback') }}');
            $('#form').append('<input type="hidden" name="_method" value="POST">');
            $('#materi_id').val(id);
            $('#modal').modal('show');
        }
    }
</script>
@endpush