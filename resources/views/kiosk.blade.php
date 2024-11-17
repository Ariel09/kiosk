<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Terminal</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            background-color: #000000;
            font-family: 'Inter', sans-serif;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .kiosk-terminal {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 80%;
        }

        .queue-display {
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 70%;
        }

        .queue-table {
            width: 100%;
            border-collapse: collapse;
        }

        .queue-table th, .queue-table td {
            padding: 20px;
            text-align: center;
            font-size: 4em;
            color: #ffcc00;
            border: 2px solid #444;
        }

        .queue-table th {
            color: #ffffff;
            background-color: #444;
            font-size: 2em;
            font-weight: 600;
        }

        .queue-table .current {
            color: #ffcc00;
        }

        .queue-table .window {
            color: #ff0000;
        }

        .logo-container {
            width: 27%;
            text-align: center;
        }

        .logo-container img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="kiosk-terminal">
        <div class="queue-display">
            <table class="queue-table">
                <thead>
                    <tr>
                        <th>Preparing</th>
                        <th>Collect</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="current" id="queueNumberDisplay">Loading...</td>
                        <td class="window" id="windowDisplay">Loading...</td>
                    </tr>
                    <tr>
                        <td class="current" id="queueNumberDisplay">Loading...</td>
                        <td class="window" id="windowDisplay">Loading...</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="logo-container">
            <img src="{{ asset('logo.png') }}" alt="Academy Logo">
        </div>
    </div>

    <script>
        // Function to fetch the latest queue number and window from the server
        function fetchQueueNumber() {
            axios.get('/get-latest-queue-number')
                .then(response => {
                    document.getElementById('queueNumberDisplay').innerText = response.data.queue_number || 'No Queue';
                    document.getElementById('windowDisplay').innerText = response.data.window || 'No Window';
                })
                .catch(error => {
                    console.error('Error fetching queue number:', error);
                    document.getElementById('queueNumberDisplay').innerText = 'Error';
                    document.getElementById('windowDisplay').innerText = 'Error';
                });
        }

        // Function to fetch the waiting list from the server
        function fetchWaitingList() {
            axios.get('/get-waiting-list')
                .then(response => {
                    const waitingList = response.data.queue_numbers;
                    const waitingListContainer = document.getElementById('waitingList');

                    waitingListContainer.innerHTML = '';

                    waitingList.forEach(queueNumber => {
                        const listItem = document.createElement('li');
                        listItem.innerText = queueNumber;
                        waitingListContainer.appendChild(listItem);
                    });
                })
                .catch(error => {
                    console.error('Error fetching waiting list:', error);
                });
        }

        setInterval(fetchQueueNumber, 5000);
        setInterval(fetchWaitingList, 5000);

        fetchQueueNumber();
        fetchWaitingList();
    </script>
</body>
</html>
