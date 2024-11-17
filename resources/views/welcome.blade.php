<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Document Request</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: "Inter", sans-serif;
        margin: 0;
        padding: 0;
        height: 100vh;
        overflow: hidden;
    }
    .main-container {
        display: flex;
        flex-direction: row;
        height: 100vh;
        overflow: hidden;
    }
    .document-selection, .cart-section {
        padding: 20px;
        overflow-y: auto;
    }
    .document-selection {
        flex: 2;
        border-right: 1px solid #ddd;
        font-size: 1.2rem;
    }
    .cart-section {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cart-header {
        margin-bottom: 20px;
    }
    .cart-items {
        flex-grow: 1;
        overflow-y: auto;
    }
    .cart-footer {
        margin-top: 20px;
        text-align: center;
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .card-item {
        flex: 1 1 calc(50% - 20px);
        min-width: 200px;
    }
    .card-button {
        width: 100%;
        height: 150px;
        font-size: 1.2rem;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
    }
    .table th, .table td {
        text-align: center;
    }
</style>

</head>
<body>
    <div class="main-container">
        <!-- Document Selection Section -->
        <div class="document-selection">
            <h2>Select Your Documents</h2>
            <div class="card-container mt-4">
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('Form 138')">Card/Form 138</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('Good Moral')">Good Moral</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('Diploma')">Diploma</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('Form 137')">Form 137</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('TOR')">Transcript of Records (TOR)</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('CTC')">Certified True Copy (CTC)</button>
                </div>
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart('COE')">Certificate of Enrollment (COE)</button>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <div class="cart-header">
                <h3>Your Cart</h3>
            </div>
            <div class="cart-items">
                <table class="table table-bordered" id="cartTable">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Quantity</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Cart items will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>
            <div class="cart-footer">
                <button class="btn btn-primary btn-lg" onclick="proceedToCheckout()">Proceed to Checkout</button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Enter Your Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        @csrf
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
                            <select class="form-control" id="year_level" name="year_level" required>
                                <option value="" disabled selected>Select your year level</option>
                                <option value="SHS">SHS: GAS</option>
                                <option value="SHS">HUMMS</option>
                                <option value="SHS">ABM</option>
                                <option value="SHS">STEM</option>
                                <option value="SHS">ICT</option>
                                <option value="SHS">HE</option>
                                <option value="BS Accountancy">BS Accountancy</option>
                                <option value="BS Criminology">BS Criminology</option>
                                <option value="BS Office Administration">BS Office Administration</option>
                                <option value="Bachelor of Technical-Vocational Teacher Education">Bachelor of Technical-Vocational Teacher Education</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
       const cart = {};

// Add item to the cart
function addToCart(documentType) {
    if (cart[documentType]) {
        cart[documentType]++;
    } else {
        cart[documentType] = 1;
    }
    updateCart();
}

// Remove item from the cart
function removeFromCart(documentType) {
    if (cart[documentType]) {
        delete cart[documentType];
        updateCart();
    }
}

// Decrease quantity
function decreaseQuantity(documentType) {
    if (cart[documentType] > 1) {
        cart[documentType]--;
    } else {
        delete cart[documentType];
    }
    updateCart();
}

// Update the cart display
function updateCart() {
    const cartTableBody = document.querySelector("#cartTable tbody");
    cartTableBody.innerHTML = ""; // Clear the table

    // Loop through cart items
    for (const [documentType, quantity] of Object.entries(cart)) {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${documentType}</td>
            <td>${quantity}</td>
            <td>
                <button class="btn btn-success btn-sm" onclick="addToCart('${documentType}')">+</button>
                <button class="btn btn-danger btn-sm" onclick="decreaseQuantity('${documentType}')">-</button>
                <button class="btn btn-secondary btn-sm" onclick="removeFromCart('${documentType}')">Remove</button>
            </td>
        `;
        cartTableBody.appendChild(row);
    }
}

// Handle "Proceed to Checkout" click
function proceedToCheckout() {
    if (Object.keys(cart).length === 0) {
        alert("Your cart is empty. Please add documents first.");
        return;
    }

    // Show the checkout modal
    $('#checkoutModal').modal('show');
}

// Handle form submission
document.getElementById("checkoutForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent default form submission

    // Check if cart is empty
    if (Object.keys(cart).length === 0) {
        alert("Your cart is empty. Please add documents first.");
        return;
    }

    // Prepare the documents array for submission
    const documents = Object.entries(cart).map(([type, quantity]) => ({
        document_type: type,
        quantity: quantity,
    }));

    // Collect user information from the form
    const formData = {
        name: document.getElementById("name").value,
        contact: document.getElementById("contact").value,
        email: document.getElementById("email").value,
        year_level: document.getElementById("year_level").value,
        documents: documents, // Include cart data
    };

    console.log("Form Data to be Sent:", formData); // Debugging: Check data before sending

    // Submit data to the server
    axios.post("/request-document", formData)
        .then(response => {
            alert(`Request submitted successfully. Your queue number is: ${response.data.queue_number}`);
            location.reload(); // Reload the page after successful submission
        })
        .catch(error => {
            console.error("Error submitting form:", error); // Log error for debugging
            alert(`An error occurred: ${error.response ? error.response.data.message : error.message}`);
        });
});

    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
