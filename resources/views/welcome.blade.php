<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Document Request</title>
    <!-- Include Bootstrap for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include Axios for AJAX requests -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", system-ui;
        }
        /* Card button styling */
        .card-button {
            width: 100%;
            height: 150px;
            font-size: 1.2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }
        /* Container for card buttonz */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        /* Individual card items */
        .card-item {
            flex: 1 1 calc(33.333% - 20px);
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Select Your Option</h2>

        <!-- Document Selection Buttons -->
        <div class="card-container mt-4">
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('Form 138')">Card/Form 138</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('Good Moral')">Good Moral</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('Diploma')">Diploma</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('Form 137')">Form 137</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('TOR')">Transcript of Records (TOR)</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('CTC')">Certified True Copy (CTC)</button>
            </div>
            <div class="card-item">
                <button class="btn btn-light border card-button" onclick="openModal('COE')">Certificate of Enrollment (COE)</button>
            </div>
        </div>

        <!-- Information Modal -->
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="infoModalLabel">Enter Your Information</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="documentRequestForm">
                            @csrf <!-- CSRF token for security -->
                            <input type="hidden" id="document_type" name="document_type">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="form-group">
                                <label for="contact">Contact Number</label>
                                <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your contact number" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="form-group">
                                <label for="year_level">Year Level</label>
                                <input type="text" class="form-control" id="year_level" name="year_level" placeholder="Enter your year level" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        <div id="statusMessage" class="mt-4"></div>
    </div>

    <script>
        // Open modal and set document type
        function openModal(documentType) {
            document.getElementById('document_type').value = documentType;
            $('#infoModal').modal('show');
        }

        // Handle form submission
        document.getElementById('documentRequestForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Prevent the default form submission

    // Get form data
    const formData = {
        name: document.getElementById('name').value,
        contact: document.getElementById('contact').value,
        email: document.getElementById('email').value,
        document_type: document.getElementById('document_type').value,
        year_level: document.getElementById('year_level').value,
    };

    // Submit the form using Axios
    axios.post('/request-document', formData)
        .then(response => {
            // Display success message
            document.getElementById('statusMessage').innerHTML = `
                <div class="alert alert-success">Request submitted successfully. Your queue number is: ${response.data.queue_number}</div>
            `;
        })
        .catch(error => {
            // Display error message
            console.error('Error:', error); // Log full error to console for debugging
            document.getElementById('statusMessage').innerHTML = `
                <div class="alert alert-danger">An error occurred while submitting your request. Please try again.</div>
            `;
        });
});

        // Set CSRF token for Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    </script>

    <!-- Include Bootstrap JS for modals -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
