<!DOCTYPE html>
<html>

<head>
    <title>S-ViC PHP Compiler</title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
    <style>
        body {
            background-color: #fff !important;
        }

        textarea {
            height: 500px;
            border-radius: 5px;
            background-color: #180a38 !important;
            color: #fff !important;
            caret-color: #fff !important;
        }

        .CodeMirror {
            height: 500px;
            border-radius: 5px;
            background-color: #180a38 !important;
            color: #fff !important;
            border: 1px solid #3d22f0 !important;
            pointer-events: auto !important;
            caret-color: #fff !important;
        }
    </style>
</head>

<body>
    <h2 class="text-center">
        S-ViC Python Compiler
    </h2>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phpCode">Enter Python Code:</label>
                    <textarea id="phpCode" class="form-control">print('Hello World')</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="output">Output:</label>
                    <textarea id="output" class="form-control" readonly></textarea>
                </div>
            </div>
            <div class="text-center">
                <button class="btn btn-primary" id="btn-compile">Compile</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- // code mirror -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>
    <script>
        $(document).ready(function () {
            var editor = CodeMirror.fromTextArea(document.getElementById("phpCode"), {
                lineNumbers: true,
                mode: "application/x-httpd-php",
                matchBrackets: true,
                indentUnit: 4,
                indentWithTabs: true,
                enterMode: "keep",
                tabMode: "shift",
                theme: "ayu-dark"
            });

            editor.setSize("100%", 500);

            $('#btn-compile').click(function () {
                $('#output').val('');
                var phpCode = editor.getValue();
                console.log(phpCode);
                compilePHP(phpCode);
            });
        });


        function compilePHP(code) {
            var phpCode =
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    url: "{{route('utils.python-compiler')}}",
                    method: "POST",
                    data: {
                        code: code
                    },
                    beforeSend: function () {
                        swal({
                            title: "Compiling...",
                            text: "Please wait...",
                            icon: "info",
                            button: false,
                            closeOnClickOutside: false,
                            closeOnEsc: false,
                        });
                    },
                    success: function (response) {
                        console.log(response);
                        promise = new Promise(function (resolve, reject) {
                            setTimeout(function () {
                                resolve(swal.close());
                            }, 1000);
                        });
                        promise.then(function (result) {
                            $('#output').val(response.data.data);
                            swal({
                                title: "Success!",
                                text: "Successfully compiled!",
                                icon: "success",
                                button: "OK",
                                timer: 700,
                            });
                        });
                    },
                    error: function (response) {
                        $('#output').val(response.responseJSON.output);
                        swal({
                            title: "Error!",
                            text: "Syntax error!",
                            icon: "error",
                            button: "OK",
                        });
                    }

                })
        }
    </script>
</body>

</html>