<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiosk Terminal</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Custom CSS for Kiosk Display -->
    <style>
        body {
            background: linear-gradient(135deg, #f0f8ff, #e6e9f0);
            font-family: Arial, sans-serif;
        }

        .kiosk-terminal {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .terminal-container {
            background-color: #ffffff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 500px;
            width: 90%;
        }

        .queue-label {
            font-size: 1.5em;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .queue-number-display {
            background-color: #f8f9fa;
            border: 3px solid #6c757d;
            border-radius: 10px;
            padding: 50px;
            text-align: center;
            font-size: 4em;
            color: #007bff;
            font-weight: bold;
            margin-top: 20px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .footer-text {
            margin-top: 20px;
            font-size: 1em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container kiosk-terminal">
        <div class="terminal-container">
            <div class="queue-label">Current Queue Number</div>
            <div id="queueNumberDisplay" class="queue-number-display">
                Loading...
            </div>
            <div class="footer-text">Please wait for your number to be called.</div>
        </div>
    </div>

    <script>
        // Function to fetch the latest queue number from the server
        function fetchQueueNumber() {
            axios.get('/get-latest-queue-number')
                .then(response => {
                    document.getElementById('queueNumberDisplay').innerText = response.data.queue_number || 'No Queue';
                })
                .catch(error => {
                    console.error('Error fetching queue number:', error);
                    document.getElementById('queueNumberDisplay').innerText = 'Error';
                });
        }

        // Fetch the queue number every 5 seconds
        setInterval(fetchQueueNumber, 5000);

        // Fetch the queue number when the page loads
        fetchQueueNumber();
    </script>
</body>
</html>