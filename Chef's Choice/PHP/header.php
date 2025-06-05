<?php
// header.php - Consistente header voor Chef's Choice
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$showAdmin = false;
if (isset($_SESSION['user_id'])) {
    include_once __DIR__ . '/DB_con.php';
    $stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && $user['role'] === 'admin') {
        $showAdmin = true;
    }
}
?>
<header class="bg-dark text-white shadow-sm">
    <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between py-3">
        <div class="d-flex align-items-center gap-2 mb-2 flex-shrink-0" style="min-width:120px; margin-left:0;">
            <img src="/Chef's Choice/images/Logo.jpg" alt="Chef's Choice logo" style="height: 100px; width: 100px; object-fit: contain; margin-left:0;">
        </div>
        <div class="flex-grow-1 ms-lg-2">
            <h1 class="mb-0 fw-bold letter-spacing-1 fs-2">Chef's Choice</h1>
            <p class="mb-0 fs-6">Dé plek voor hoogwaardige keukenproducten en culinaire inspiratie.</p>
        </div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-0 w-100 mt-3 mt-lg-0">
            <div class="container-fluid p-0">
                <button class="navbar-toggler ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Navigatie wisselen">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-2">
                            <a class="nav-link text-dark fs-5 fw-bold px-4 py-2 rounded bg-info bg-opacity-75" href="/Chef's Choice/Index.php">Home</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link text-dark fs-5 fw-bold px-4 py-2 rounded bg-info bg-opacity-75" href="/Chef's Choice/PHP/Bestel.php">Producten</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="nav-link text-dark fs-5 fw-bold px-4 py-2 rounded bg-info bg-opacity-75" href="/Chef's Choice/Index.php#about">Over Ons</a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-dark fs-5 fw-bold px-4 py-2 rounded bg-info bg-opacity-75" href="/Chef's Choice/Index.php#contact">Contact</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center text-white fs-5 fw-bold px-3 py-2 rounded bg-primary bg-opacity-75 ms-2" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-2"></i> Account
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
                                <li><a class="dropdown-item" href="/Chef's Choice/PHP/Account.php"><i class="bi bi-person me-2"></i> Mijn account</a></li>
                                <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a class="dropdown-item" href="/Chef's Choice/PHP/Uitloggen.php"><i class="bi bi-box-arrow-right me-2"></i> Uitloggen</a></li>
                                <?php else: ?>
                                <li><a class="dropdown-item" href="/Chef's Choice/PHP/Inlog.php"><i class="bi bi-box-arrow-in-right me-2"></i> Inloggen</a></li>
                                <li><a class="dropdown-item" href="/Chef's Choice/PHP/Registratie.php"><i class="bi bi-person-plus me-2"></i> Registreren</a></li>
                                <?php endif; ?>
                                <?php if ($showAdmin): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/Chef's Choice/PHP/Productbeheer.php"><i class="bi bi-tools me-2"></i> Productbeheer</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-warning" href="/Chef's Choice/PHP/Reviews.php"><i class="bi bi-star-half me-2"></i> Reviews</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
