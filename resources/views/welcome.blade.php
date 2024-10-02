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
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Kiosk Document Request</h2>

        <!-- Document Request Form -->
        <form id="documentRequestForm" method="POST" action="/request-document">
            @csrf <!-- CSRF token for security -->

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
                <label for="document_type">Select Document</label>
                <select class="form-control" id="document_type" name="document_type" required>
                    <option value="Form 138">Form 138</option>
                    <option value="Form 137">Form 137</option>
                    <option value="Good Moral">Good Moral</option>
                    <option value="Diploma">Diploma</option>
                    <option value="TOR">Transcript of Records (TOR)</option>
                    <option value="CTC">Certified True Copy (CTC)</option>
                    <option value="COE">Certificate of Enrollment (COE)</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year_level">Year Level</label>
                <input type="text" class="form-control" id="year_level" name="year_level" placeholder="Enter your year level" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>

        <!-- Status Message -->
        <div id="statusMessage" class="mt-4"></div>
    </div>

    <script>
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
                <div class="alert alert-danger">An error occurred: ${error.response ? error.response.data.message : error.message}</div>
            `;
        });
});
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
</body>
</html>