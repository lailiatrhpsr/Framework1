<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - FoodApp</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-purple: #a855f7;
            --hover-purple: #9333ea;
            --bg-light: #f4f7fe;
            --text-dark: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Navbar Styling */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 0;
        }
        .navbar-brand {
            font-weight: 800;
            color: var(--primary-purple) !important;
            letter-spacing: -1px;
            font-size: 1.5rem;
        }
        .nav-link {
            color: #64748b !important;
            font-weight: 600;
            margin: 0 10px;
            transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-purple) !important;
        }

        /* Reusable Components */
        .btn-purple {
            background-color: var(--primary-purple);
            color: white;
            border: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-purple:hover {
            background-color: var(--hover-purple);
            color: white;
            transform: translateY(-2px);
        }
        .card {
            border-radius: 16px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.08) !important;
        }
        .badge-purple-soft {
            background-color: #f3e8ff;
            color: var(--primary-purple);
            font-weight: 600;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('customer.menus') }}">
                <i class="bi bi- lightning-charge-fill me-2"></i> RestArea Lailia
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('customer.menus') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 position-relative" href="{{ route('customer.cart') }}">
                            <i class="bi bi-bag-heart fs-5"></i>
                            <span class="badge rounded-pill bg-danger position-absolute top-0 start-100 translate-middle" style="font-size: 0.6rem;">
                                {{ session('cart') ? count(session('cart')) : 0 }}
                            </span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <button class="btn btn-purple rounded-pill px-4">Masuk</button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="py-5 text-center text-muted small">
        <hr class="container mb-4">
        <p>© 2026 FoodApp. Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk Pecinta Kuliner.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>