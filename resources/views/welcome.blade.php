<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kiosk Document Request</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Inter", sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }

        nav {
            background-color: #800505;
            color: white;
            padding: 10px 20px;
        }

        nav .btn-custom {
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            padding: 5px 10px;
            margin-left: 10px;
            text-decoration: none;
        }

        nav .btn-logout {
            color: gray;
            border: 1px solid white;
            border-radius: 5px;
            padding: 5px 10px;
            margin-left: 10px;
            text-decoration: none;
        }

        nav .btn-custom:hover {
            background-color: white;
            color: #007bff;
        }

        .main-container {
            display: flex;
            flex-direction: row;
            height: calc(100vh - 50px);
            overflow: hidden;
        }

        .document-selection,
        .cart-section {
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
            height: 5rem;
            font-size: 1.2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
        }

        @media (max-width: 1024px) {
            body {
                font-size: 14px;
            }

            nav h4 {
                font-size: 1.5rem;
            }

            .btn-custom {
                font-size: 0.9rem;
                padding: 5px 10px;
            }

            .document-selection h2 {
                font-size: 1.4rem;
            }

            .card-button {
                font-size: 1rem;
                height: 4rem;
            }

            .cart-section h3 {
                font-size: 1.2rem;
            }

            .cart-items table th,
            .cart-items table td {
                font-size: 0.9rem;
            }

            .cart-footer .btn {
                font-size: 1rem;
                padding: 10px 20px;
            }

            /* Adjust cart width slightly larger for tablets */
            .document-selection {
                flex: 1.8;
            }

            .cart-section {
                flex: 1.2;
                /* Slightly increase width of cart section */
            }
        }

        @media (max-width: 768px) {
            body {
                font-size: 13px;
            }

            nav h4 {
                font-size: 1.4rem;
            }

            .btn-custom {
                font-size: 0.8rem;
                padding: 4px 8px;
            }

            .document-selection h2 {
                font-size: 1.3rem;
            }

            .card-button {
                font-size: 0.9rem;
                height: 3.5rem;
            }

            .cart-section h3 {
                font-size: 1.1rem;
            }

            .cart-items table th,
            .cart-items table td {
                font-size: 0.8rem;
            }

            .cart-footer .btn {
                font-size: 0.9rem;
                padding: 8px 16px;
            }

            /* Further adjustment for smaller devices */
            .document-selection {
                flex: 1.6;
            }

            .cart-section {
                flex: 1.4;
                /* Further increase cart section width */
            }
        }
    </style>
</head>

<body>

    <!-- Navigation Bar -->
    <nav class="d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h4 class="mb-0">Kiosk Document Request</h4>
        </div>
        <div>
            @if(Route::has('filament.admin.auth.login'))
            <div class="d-flex align-items-center">
                @auth
                @if(auth()->user()?->student)
                <span class="text-white">Welcome, {{ auth()->user()->student->full_name }}</span>
                @else
                <span class="text-white">Welcome, {{ auth()->user()->name ?? 'User' }}</span>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="ml-3">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
                @else
                <a href="{{ route('filament.admin.auth.login') }}" class="btn-custom">LOGIN</a>
                @if(Route::has('register'))
                <a href="{{ route('register') }}" class="btn-custom">Register</a>
                @endif
                @endauth
            </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Document Selection Section -->
        <div class="document-selection">
            <h2>Select Your Documents</h2>
            <div class="card-container mt-4">
                @foreach ($documents as $document)
                <div class="card-item">
                    <button class="btn btn-light border card-button" onclick="addToCart({{ $document->id }}, '{{ $document->document_name }}', {{ $document->price }})">
                        {{ $document->document_name }} - ₱{{ number_format($document->price, 2) }}
                    </button>
                </div>
                @endforeach

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
                            <th>Price</th> <!-- Added title for the price column -->
                            <th>Actions</th> <!-- Title for the actions column -->
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
        <!-- Privacy Policy Modal -->
        <div class="modal fade" id="privacyPolicyModal" tabindex="-1" role="dialog" aria-labelledby="privacyPolicyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="privacyPolicyModalLabel">Data Privacy Policy</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>I hereby affirm that all information supplied herein is complete and accurate. Withholding or giving false information will make me ineligible for admission or subject to dismissal. If admitted, I agree to abide by the established guidelines of Pamantasan ng Cabuyao.</p>

                        <p> Further, I agree to collection and processing of my data for the purpose of processing request for school records at Pamantasan ng Cabuyao. I understand that my personal information is protected by RA 10173, Data Privacy Act of 2012, and that I am required to provide truthful information. I understand that my personal information shall not be shared or disclosed with other parties without consent unless the disclosure is required by, or in compliance with, applicable laws and regulations.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="agreePrivacyPolicy">Agree</button>
                    </div>
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
                            <input type="hidden" id="user_id" value="{{ Auth::user()->id }}">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ auth()->user()->student->full_name ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="contact">Contact Number</label>
                                <input type="text" class="form-control" id="contact" name="contact" placeholder="Enter your contact number" value="{{ auth()->user()->student->contact_number ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ auth()->user()->email ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="program">Year Level</label>
                                <select class="form-control" id="year_level" name="year_level" onchange="updatePrograms()" required>
                                    <option value="" disabled selected>Select your year level</option>
                                    <option value="1st year">1st year</option>
                                    <option value="2nd year">2nd year</option>
                                    <option value="3rd year">3rd year</option>
                                    <option value="4th year">4th year</option>
                                    <option value="SHS">SHS</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="program">Program</label>
                                <select class="form-control" id="program" name="program" required>
                                    <option value="" disabled selected>Select program</option>
                                    <!-- Program options will be populated dynamically -->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <script>
            let cart = {}; // To store the cart items, with document ID as the key and name as the value

            function addToCart(documentId, documentName, documentPrice) {
                // Check if the document is already in the cart
                if (cart[documentId]) {
                    cart[documentId].quantity++; // Increase quantity if already in the cart
                    cart[documentId].totalPrice += documentPrice; // Update total price
                } else {
                    cart[documentId] = {
                        name: documentName,
                        price: documentPrice,
                        quantity: 1,
                        totalPrice: documentPrice
                    }; // Add new document to the cart
                }
                updateCart(); // Update the cart display after adding the item
            }

            function decreaseQuantity(documentId) {
                if (cart[documentId]) {
                    if (cart[documentId].quantity > 1) {
                        cart[documentId].quantity--; // Decrease quantity
                        cart[documentId].totalPrice -= cart[documentId].price; // Adjust total price
                    } else {
                        removeFromCart(documentId); // Remove item if quantity is 1
                    }
                }
                updateCart();
            }

            function removeFromCart(documentId) {
                delete cart[documentId]; // Remove item from cart
                updateCart();
            }

            function updateCart() {
                const cartTableBody = document.querySelector("#cartTable tbody");
                cartTableBody.innerHTML = ""; // Clear the table
                let grandTotal = 0; // Initialize grand total

                // Loop through cart items
                for (const [documentId, documentDetails] of Object.entries(cart)) {
                    const row = document.createElement("tr");

                    row.innerHTML = `
            <td>${documentDetails.name}</td> <!-- Display document name -->
            <td>${documentDetails.quantity}</td>
            <td>₱${documentDetails.totalPrice.toFixed(2)}</td> <!-- Show price per document -->
            <td>
                <button class="btn btn-success btn-sm" onclick="addToCart(${documentId}, '${documentDetails.name}', ${documentDetails.price})">+</button>
                <button class="btn btn-danger btn-sm" onclick="decreaseQuantity(${documentId})">-</button>
                <button class="btn btn-secondary btn-sm" onclick="removeFromCart(${documentId})">Remove</button>
            </td>
        `;
                    cartTableBody.appendChild(row);
                    grandTotal += documentDetails.totalPrice; // Update grand total
                }

                // Display grand total
                const totalRow = document.createElement("tr");
                totalRow.innerHTML = `
        <td colspan="2"><strong>Total</strong></td>
        <td><strong>₱${grandTotal.toFixed(2)}</strong></td>
        <td></td> <!-- Empty column for alignment -->
    `;
                cartTableBody.appendChild(totalRow);
            }


            // Adjusting the "Proceed to Checkout" button
            function proceedToCheckout() {
                if (Object.keys(cart).length === 0) {
                    alert("Your cart is empty. Please add documents first.");
                    return;
                }

                // Show the checkout modal
                $('#privacyPolicyModal').modal('show');
            }

            // Handle Agree button click inside Privacy Policy Modal
            document.getElementById("agreePrivacyPolicy").addEventListener("click", function() {
                $('#privacyPolicyModal').modal('hide'); // Hide Privacy Policy Modal
                $('#checkoutModal').modal('show'); // Show Checkout Modal
            });

            // Handle form submission
            document.getElementById("checkoutForm").addEventListener("submit", function(e) {
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

                console.log(cart);

                // Collect user information from the form
                const formData = {
                    user_id: document.getElementById("user_id").value, // Add user_id here
                    name: document.getElementById("name").value,
                    contact: document.getElementById("contact").value,
                    email: document.getElementById("email").value,
                    year_level: document.getElementById("year_level").value,
                    program: document.getElementById("program").value,
                    documents: Object.entries(cart).map(([id, details]) => ({
                        document_type: id,
                        quantity: details.quantity,
                        price: details.totalPrice,
                    })),
                };


                console.log("Form Data to be Sent:", formData); // Debugging: Check data before sending

                // Submit data to the server
                axios.post("/request-document", formData)
                    .then(response => {
                        Swal.fire({
                            title: 'Request Submitted!',
                            text: `Your queue number is: ${response.data.queue_number}`,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload(); // Reload the page after the user acknowledges the alert
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: `An error occurred: ${error.response ? error.response.data.message : error.message}`,
                            icon: 'error',
                            confirmButtonText: 'Try Again'
                        });
                        console.error("Error submitting form:", error); // Log error for debugging
                    });
            });


            //Handle the conditional programs depending on their year Level

            function updatePrograms() {
                const yearLevel = document.getElementById("year_level").value; // Get the selected year level
                const programSelect = document.getElementById("program"); // Get the program dropdown
                programSelect.innerHTML = '<option value="" disabled selected>Select program</option>'; // Reset options

                // Define programs based on year level
                const programsByYearLevel = {
                    "1st year": [{
                            value: "BS Accountancy",
                            text: "BS Accountancy (BSA)"
                        },
                        {
                            value: "BS Criminology",
                            text: "BS Criminology (BSC)"
                        },
                        {
                            value: "BS Office Administration",
                            text: "BS Office Administration (BSOA)"
                        },
                        {
                            value: "BS Psychology",
                            text: "BS Psychology (BSP)"
                        },
                        {
                            value: "Bachelor of Technical-Vocational Teacher Education",
                            text: "Bachelor of Technical-Vocational Teacher Education (BTVTEd)"
                        },
                    ],
                    "2nd year": [{
                            value: "BS Accountancy",
                            text: "BS Accountancy (BSA)"
                        },
                        {
                            value: "BS Criminology",
                            text: "BS Criminology (BSC)"
                        },
                        {
                            value: "BS Office Administration",
                            text: "BS Office Administration (BSOA)"
                        },
                        {
                            value: "BS Psychology",
                            text: "BS Psychology (BSP)"
                        },
                        {
                            value: "Bachelor of Technical-Vocational Teacher Education",
                            text: "Bachelor of Technical-Vocational Teacher Education (BTVTEd)"
                        },
                    ],
                    "3rd year": [{
                            value: "BS Accountancy",
                            text: "BS Accountancy (BSA)"
                        },
                        {
                            value: "BS Criminology",
                            text: "BS Criminology (BSC)"
                        },
                        {
                            value: "BS Office Administration",
                            text: "BS Office Administration (BSOA)"
                        },
                        {
                            value: "BS Psychology",
                            text: "BS Psychology (BSP)"
                        },
                        {
                            value: "Bachelor of Technical-Vocational Teacher Education",
                            text: "Bachelor of Technical-Vocational Teacher Education (BTVTEd)"
                        },
                    ],
                    "4th year": [{
                            value: "BS Accountancy",
                            text: "BS Accountancy (BSA)"
                        },
                        {
                            value: "BS Criminology",
                            text: "BS Criminology (BSC)"
                        },
                        {
                            value: "BS Office Administration",
                            text: "BS Office Administration (BSOA)"
                        },
                        {
                            value: "BS Psychology",
                            text: "BS Psychology (BSP)"
                        },
                        {
                            value: "Bachelor of Technical-Vocational Teacher Education",
                            text: "Bachelor of Technical-Vocational Teacher Education (BTVTEd)"
                        },
                    ],
                    SHS: [{
                            value: "GAS",
                            text: "GAS"
                        },
                        {
                            value: "HUMMS",
                            text: "HUMMS"
                        },
                        {
                            value: "ABM",
                            text: "ABM"
                        },
                        {
                            value: "STEM",
                            text: "STEM"
                        },
                        {
                            value: "ICT",
                            text: "ICT"
                        },
                        {
                            value: "HE",
                            text: "HE"
                        },
                    ],
                };

                // Populate the program dropdown with the relevant options
                if (programsByYearLevel[yearLevel]) {
                    programsByYearLevel[yearLevel].forEach((program) => {
                        const option = document.createElement("option");
                        option.value = program.value;
                        option.textContent = program.text;
                        programSelect.appendChild(option);
                    });
                }
            }
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>