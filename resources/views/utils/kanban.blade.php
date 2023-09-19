<!DOCTYPE html>
<html>

<head>
    <title>
        Kanban Board
    </title>
    <link rel="stylesheet" href="{{ asset('assets/css/main/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/main/app-dark.css') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://kit.fontawesome.com/1d954ea888.js"></script>
    <style>
        .card {
            border-radius: 10px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 10px 10px 0 0;
            padding: 0.7rem 1rem;
        }
    </style>
</head>

<body class="bg-white">
    <h2 class="text-center mt-4">
        Kanban Board
    </h2>
    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="text-white">Backlog</h5>
                    </div>
                    <ul class="list-group list-group-flush sortable" id="backlog">

                    </ul>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" onclick="showInputPrompt('backlog')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="text-white">Doing</h5>
                    </div>
                    <ul class="list-group list-group-flush sortable" id="doing">

                    </ul>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" onclick="showInputPrompt('doing')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="text-white">Review</h5>
                    </div>
                    <ul class="list-group list-group-flush sortable" id="review">

                    </ul>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" onclick="showInputPrompt('review')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="text-white">Done</h5>
                    </div>
                    <ul class="list-group list-group-flush sortable" id="done">

                    </ul>
                    <div class="card-footer">
                        <button class="btn btn-success btn-sm" onclick="showInputPrompt('done')">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        const lists = document.getElementsByClassName('sortable');

        Array.from(lists).forEach(list => {
            new Sortable(list, {
                group: 'shared',
                animation: 150,
                onEnd: function (evt) {
                    saveData();
                }
            });
        });

        function showInputPrompt(listId) {
            const textSwal = swal({
                title: 'Tambah Task',
                text: 'Masukkan nama task',
                content: {
                    element: 'input',
                    attributes: {
                        placeholder: 'Nama task',
                        type: 'text',
                    },
                },
                buttons: {
                    cancel: {
                        text: 'Batal',
                        value: null,
                        visible: true,
                        className: '',
                        closeModal: true,
                    },
                    confirm: {
                        text: 'Tambah',
                        value: true,
                        visible: true,
                        className: '',
                        closeModal: true
                    }
                }
            }).then((value) => {
                if (value) {
                    addCard(listId, value);
                }
            })
        }

        function addCard(listId, taskText) {
            const list = document.getElementById(listId);
            const cardCount = list.getElementsByTagName('li').length;
            const newCard = document.createElement('li');
            newCard.classList.add('list-group-item');
            newCard.innerHTML = `
        <span class="task-text">${taskText}</span>
        <div class="btn-group float-end">
          <button class="btn btn-danger btn-sm" onclick="deleteCard(this)"><i class="fas fa-trash"></i>
            </button>
        </div>
      `;
            list.appendChild(newCard);
            saveData();
        }

        function deleteCard(btn) {
            const card = btn.closest('.list-group-item');
            const list = card.closest('.sortable');
            list.removeChild(card);
            saveData();
        }

        function saveData() {
            const lists = document.getElementsByClassName('sortable');
            const data = {};
            Array.from(lists).forEach((list, index) => {
                const listId = list.getAttribute('id');
                const cards = list.getElementsByTagName('li');
                data[listId] = [];
                Array.from(cards).forEach(card => {
                    const taskText = card.querySelector('.task-text');
                    data[listId].push(taskText.textContent);
                });
            });
            localStorage.setItem('trelloData', JSON.stringify(data));
        }

        function loadData() {
            const data = localStorage.getItem('trelloData');
            if (data) {
                const parsedData = JSON.parse(data);
                for (const listId in parsedData) {
                    const list = document.getElementById(listId);
                    if (list) {
                        list.innerHTML = '';
                        parsedData[listId].forEach(taskText => {
                            const newCard = document.createElement('li');
                            newCard.classList.add('list-group-item');
                            newCard.innerHTML = `
                <span class="task-text">${taskText}</span>
                <div class="btn-group float-end">
                  <button class="btn btn-danger btn-sm" onclick="deleteCard(this)">
                    <i class="fas fa-trash"></i>
                    </button>
                </div>
              `;
                            list.appendChild(newCard);
                        });
                    }
                }
            }
        }
        loadData();
    </script>
</body>

</html>