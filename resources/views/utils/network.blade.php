<!DOCTYPE html>
<html>

<head>
    <title>Drag and Drop Network Simulation</title>
    <style>
        .device {
            width: 100px;
            height: 100px;
            background-color: #ccc;
            border: 1px solid #000;
            border-radius: 5px;
            position: absolute;
        }

        .device-label {
            text-align: center;
            margin-top: 40px;
        }

        .connection {
            position: absolute;
            background-color: #000;
            z-index: -1;
        }

        #canvas {
            width: 800px;
            height: 400px;
            background-color: #f2f2f2;
            position: relative;
            border: 1px solid #000;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h2>Drag and Drop Network Simulation</h2>
    <div id="canvas">
        <div id="device-1" class="device" draggable="true">
            <div class="device-label">Device 1</div>
        </div>
        <div id="device-2" class="device" draggable="true">
            <div class="device-label">Device 2</div>
        </div>
        <div id="device-3" class="device" draggable="true">
            <div class="device-label">Device 3</div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script>
        $(document).ready(function() {
            var canvas = document.getElementById('canvas');
            var devices = [];

            $('.device').on('dragstart', function(event) {
                event.originalEvent.dataTransfer.setData('text/plain', event.target.id);
            });

            canvas.addEventListener('dragover', function(event) {
                event.preventDefault();
            });

            canvas.addEventListener('drop', function(event) {
                event.preventDefault();
                var deviceId = event.dataTransfer.getData('text/plain');
                var device = document.getElementById(deviceId);

                var x = event.clientX - canvas.offsetLeft;
                var y = event.clientY - canvas.offsetTop;

                device.style.left = x + 'px';
                device.style.top = y + 'px';

                devices.push(deviceId);

                if (devices.length > 1) {
                    var previousDeviceId = devices[devices.length - 2];
                    var previousDevice = document.getElementById(previousDeviceId);

                    var connection = document.createElement('div');
                    connection.classList.add('connection');
                    connection.style.left = (parseInt(previousDevice.style.left) + 50) + 'px';
                    connection.style.top = (parseInt(previousDevice.style.top) + 50) + 'px';
                    connection.style.width = (x - parseInt(previousDevice.style.left) - 50) + 'px';
                    connection.style.height = (y - parseInt(previousDevice.style.top) - 50) + 'px';
                    canvas.appendChild(connection);
                }
            });
        });
    </script>
</body>

</html>