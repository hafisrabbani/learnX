@extends('layouts.main')

@section('page-heading', 'Create Forum')
@section('_title', 'Create Forum')
@section('page-content')
    <section class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="container">
                    <div class="card-body">
                        <form method="POST" id="form">
                            <div class="form-group mb-2">
                                <label for="matkul_id">Matkul </label>
                                <select name="matkul_id" class="form-control" id="matkul_id">
                                    <option value="" selected disabled>-- Pilih Matkul --</option>
                                    @foreach ($matkul as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_mk }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="materi_id">Materi </label>
                                <select name="materi_id" class="form-control">
                                    <option value="" selected disabled>-- Pilih Materi --</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="title">Title</label>
                                <input type="text" name="title" placeholder="Your title" class="form-control">
                            </div>
                            <div class="form-group mb-2">
                                <label for="title">Your Questions</label>
                                <textarea class="ckeditor form-control" name="wysiwyg-editor"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('wysiwyg-editor', {
            filebrowserUploadUrl: "{{ route('forums.file-uploads', ['_token' => csrf_token()]) }}",
            filebrowserUploadMethod: 'form'
        });

        $('#matkul_id').on('change', function() {
            var id = $(this).val();
            $.ajax({
                url: "{{ route('forums.data') }}",
                method: "GET",
                data: {
                    matkul_id: id
                },
                success: function(response) {
                    console.log(response);
                    var data = response.data;
                    var html = '<option value="" selected disabled>-- Pilih Materi --</option>';
                    $.each(data, function(index, item) {
                        html += '<option value="' + item.id + '">' + item.judul_materi +
                            '</option>';
                    });
                    $('select[name="materi_id"]').html(html);
                }
            });
        })

        $('#form').on('submit', function(e) {
            e.preventDefault();
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            var data = $(this).serialize();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });

            $.ajax({
                url: "{{ route('forums.store') }}",
                method: "POST",
                data: data,
                success: function(response) {
                    swal({
                        title: "Success!",
                        text: "Your forum has been created!",
                        icon: "success",
                        button: "OK",
                    }).then(function() {
                        window.location.href = "{{ route('forums.index') }}";
                    });
                },
                error: function(response) {
                    swal({
                        title: "Error!",
                        text: "Something went wrong!",
                        icon: "error",
                        button: "OK",
                    });
                }
            });
        });
    </script>
@endpush
