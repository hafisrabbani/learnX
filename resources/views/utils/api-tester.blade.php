<!DOCTYPE html>
<html>

<head>
    <title>API Testing Tool</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
        }

        h2 {
            margin-bottom: 20px;
        }

        #request {
            width: 100%;
            margin-bottom: 20px;
        }

        #response {
            width: 100%;
            height: 200px;
        }

        #url,
        #method {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        #headers {
            margin-bottom: 10px;
        }

        #headers textarea {
            width: 100%;
            height: 100px;
            padding: 5px;
            margin-bottom: 10px;
        }

        #body {
            width: 100%;
            height: 100px;
            padding: 5px;
        }

        #send-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        #send-btn:hover {
            background-color: #45a049;
        }

        input[type=text],
        select,
        textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            margin-bottom: 10px;
            resize: vertical;
        }
    </style>
</head>

<body>
    <div class="container pt-5">
        <h2>API Testing Tool</h2>
        <p>Use this tool to test your API endpoints.
            <br>
            <small>NOTE: This tool is for testing purposes only. Do not use this tool in production.</small>
            <br>
            <small class="text-muted">Build by threeMusketeers from <a href="https://pens.ac.id">PENS</a></small>
        </p>
        <div id="request">
            <input type="text" id="url" placeholder="Enter URL..." value="https://api.example.com">
            <br>
            <select id="method">
                <option value="GET">GET</option>
                <option value="POST">POST</option>
                <option value="PUT">PUT</option>
                <option value="DELETE">DELETE</option>
            </select>
            <br>
            <div id="headers">
                <label for="headers">Headers:</label><br>
                <textarea id="headers" placeholder="Enter headers..." rows="5"></textarea>
            </div>
            <div>
                <label for="body">Body:</label><br>
                <textarea id="body" placeholder="Enter request body..." rows="10"></textarea>
            </div>
            <div>
                <button id="send-btn">Send Request</button>
            </div>
        </div>

        <h2>Response</h2>

        <textarea id="response" readonly></textarea>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://unpkg.com/sweetalert@2.1.2/dist/sweetalert.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#send-btn').on('click', function() {
                var url = $('#url').val();
                var method = $('#method').val();
                var headers = $('#headers').val();
                var body = $('#body').val();
                if (headers == '') headers = '{}';
                var request = {
                    url: url,
                    method: method,
                    headers: JSON.parse(headers),
                    body: body
                };

                $.ajax({
                    url: request.url,
                    type: request.method,
                    headers: request.headers,
                    data: request.body,
                    success: function(data) {
                        setInterval(function() {
                            swal.close();
                        }, 500)
                        $('#response').val(JSON.stringify(data, null, 4));
                    },
                    error: function(error) {
                        setInterval(function() {
                            swal.close();
                        }, 500)
                        $('#response').val(JSON.stringify(error, null, 4));
                    },
                    beforeSend: function() {
                        swal({
                            title: 'Loading...',
                            text: 'Please wait',
                            buttons: false,
                            closeOnEsc: false,
                            closeOnClickOutside: false,
                        });
                    },
                });
            });
        });
    </script>
</body>

</html>