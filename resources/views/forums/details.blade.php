@extends('layouts.main')

@section('page-heading','Forums')
@section('_title','Forums')
@section('css')
<style>
    .verified {
        border-left: 4px solid var(--bs-blue);
    }

    .card-header {
        margin-bottom: -1.5rem;
    }
</style>
@endsection
@section('page-content')
<section class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="container">
                <div class="card-header">
                    <h5 class="fw-bold">
                        {{ $data->judul }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted text-sm"><i class="fas fa-clock"></i>&nbsp;&nbsp;{{
                        $data->created_at->diffForHumans() }} | <i class="fas fa-user"></i> {{ $data->user->name }} | <i
                            class="fas fa-comments"></i>&nbsp;&nbsp;{{ $data->threads->count() }} comments</p>
                    <p><span class="badge bg-primary text-white p-2">#{{ $data->materi->matkul->nama_mk
                            }}</span>&nbsp;<span class="badge bg-success text-white p-2">#{{ $data->materi->judul_materi
                            }}</span></p>
                    <div class="text-muted">
                        {!! $data->konten !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<h4 class="mx-1 my-4">Comments</h4>
<section class="row">
    @if($data->threads->count() > 0)
    @foreach ($data->threads as $item)
    <div class="col-12">
        <div class="card shadow verified">
            <div class="container">
                <div class="card-body">
                    @php
                    $is_verified = $item->is_verified == 0 && auth()->user()->role == 'dosen' ? true : false;
                    @endphp
                    @if($is_verified)
                    <div class="text-end">
                        <button class="btn btn-sm btn-primary my-2" onclick="verifyAnswer({{ $item->id }})"><i
                                class="fas fa-check-circle"></i>&nbsp;Verify Answer</button>
                    </div>
                    @endif
                    <p class="text-muted text-sm">
                        <i class="fas fa-clock"></i>&nbsp;&nbsp;{{ $item->created_at->diffForHumans() }} |
                        <i class="fas fa-user"></i> {{ $item->user->name }}
                        @if($item->is_verified == 1)
                        | <span class="text-success text-sm"><i class="fas fa-check-circle"></i>&nbsp;Verified
                            Answer</span>
                        @endif
                    </p>
                    {!! $item->konten !!}
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    @php $is_verified = false; @endphp
    @endif
</section>

<h4 class="mx-1 my-4">Your Answer</h4>
<section class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="container">
                <div class="card-body">
                    <form method="POST" id="form">
                        <div class="form-group">
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
        filebrowserUploadUrl: "{{route('forums.file-uploads', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

    @if ($is_verified)

        function verifyAnswer(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            });
            swal({
                title: "Are you sure?",
                text: "Once verified, you will not be able to undo this action!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function (willVerify) {
                if (willVerify) {
                    $.ajax({
                        url: "{{ route('forums.verify') }}",
                        method: "POST",
                        data: {
                            id: id
                        },
                        success: function (result) {
                            swal({
                                title: "Success!",
                                text: "Your answer has been verified!",
                                icon: "success",
                                button: "OK",
                            }).then(function () {
                                window.location = "{{ route('forums.show', $data->id) }}";
                            });
                        },
                        error: function (xhr, status, error) {
                            swal({
                                title: "Error!",
                                text: "Something went wrong!",
                                icon: "error",
                                button: "OK",
                            });
                        }
                    })
                }
            })
        }
    @endif

    $('#form').on('submit', function (e) {
        e.preventDefault();
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });
        var data = new FormData(this);
        data = {
            konten: data.get('wysiwyg-editor'),
            forum_id: "{{ $data->id }}",
        }
        $.ajax({
            url: "{{ route('forums.reply') }}",
            method: "POST",
            data: data,
            success: function (result) {
                swal({
                    title: "Success!",
                    text: "Your answer has been submitted!",
                    icon: "success",
                    button: "OK",
                }).then(function () {
                    window.location = "{{ route('forums.show', $data->id) }}";
                });
            },
            error: function (xhr, status, error) {
                swal({
                    title: "Error!",
                    text: "Something went wrong!",
                    icon: "error",
                    button: "OK",
                });
            }
        })
    })
</script>
@endpush