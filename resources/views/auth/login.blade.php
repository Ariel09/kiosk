<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: "Inter", sans-serif;
    }

    .btn-login {
        color: white;
        background-color: #800505;
    }

    .btn-login:hover {
        color: white;
        background-color: #5c0202;
    }

    /* Landing Overlay Styling */
    #landingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 1000;
            transition: opacity 0.5s ease, visibility 0.5s ease;
            cursor: pointer; /* Make the whole overlay clickable */
        }
        #landingOverlay.hidden {
            opacity: 0;
            visibility: hidden;
        }
        #landingOverlay h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

/* Main content hidden initially */
        #mainContent {
            display: none;
            height: 100%;
            overflow: auto;
        }
        /* Show main content when overlay is hidden */
        #mainContent.active {
            display: block;
        }

</style>

<body>
    <!-- Landing Overlay -->
    <div id="landingOverlay">
        <img src="{{ asset('logo.png') }}" alt="Academy Logo" style="max-width: 20%; margin-bottom: 5rem;">
        <h1>Hello, Welcome to the Saint Ignatius Academy!</h1>
        <div id="continueButton">Tap the screen to proceed</div>
    </div>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-md" style="width: 400px;">
            <img src="{{ asset('logo.png') }}" alt="Academy Logo" style="max-width: 20%; margin-bottom: 1rem;" class="d-block mx-auto">
            <h3 class="text-center mb-4">Student Login</h3>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="student_number" class="form-label">Student Number</label>
                    <input
                        type="text"
                        name="student_number"
                        id="student_number"
                        class="form-control @error('student_number') is-invalid @enderror"
                        value="{{ old('student_number') }}"
                        required
                        autofocus>
                    @error('student_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control @error('password') is-invalid @enderror"
                        required>
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-login w-100">Login</button>
            </form>
        </div>
    </div>

    <script>
                // Handle tapping anywhere on the landing overlay to proceed
                document.getElementById('landingOverlay').addEventListener('click', function () {
            const overlay = document.getElementById('landingOverlay');
            overlay.classList.add('hidden');

            // Show main content after the transition
            setTimeout(() => {
                overlay.style.display = 'none';
                document.getElementById('mainContent').classList.add('active');
            }, 500); // Match the CSS transition duration
        });
    </script>
</body>

</html>
