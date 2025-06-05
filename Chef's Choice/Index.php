<?php
session_start();
$showAdmin = false;
if (isset($_SESSION['user_id'])) {
    include_once 'PHP/DB_con.php';
    $stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && $user['role'] === 'admin') {
        $showAdmin = true;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chef's Choice - Dé plek voor hoogwaardige keukenproducten en culinaire inspiratie. Ontdek nieuwe smaken en culinaire hoogstandjes!">
    <meta name="keywords" content="recepten, koken, eten, culinair, makkelijke recepten, heerlijke recepten, Chef's Choice">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Chef's Choice</title>
</head>
<body>
    <?php include_once __DIR__ . '/PHP/header.php'; ?>

    <!-- Hero Banner met Deal -->
    <section class="hero-chef10 d-flex align-items-center justify-content-center text-center position-relative mb-5" style="min-height: 340px; background: linear-gradient(120deg, #e3eafc 60%, #f8fafc 100%); overflow: hidden; color: #111;">
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0" style="background: url('images/banner-foto.jpg') center center/cover no-repeat; opacity: 0.5; pointer-events: none;"></div>
        <div class="container position-relative z-2 py-5">
            <span class="badge bg-primary text-white fs-5 mb-3 px-4 py-2 shadow">Actie</span>
            <h1 class="display-4 fw-bold text-primary mb-3">10% Korting op Alles!</h1>
            <p class="lead mb-4">Gebruik de code <span class="fw-bold text-primary text-uppercase">CHEF10</span> bij je bestelling.<br>Geldig op het hele assortiment – mis deze kans niet!</p>
            <a href="PHP/Producten.php" class="btn btn-primary btn-lg rounded-pill px-5 py-3 fw-semibold shadow d-inline-flex align-items-center gap-2 banner-cta-chef10" style="font-size: 1.25rem;">
                <i class="bi bi-bag-check-fill fs-4"></i>
                <span>Bekijk producten</span>
            </a>
        </div>
    </section>
    <!-- Einde Hero Banner -->

    <section id="products" class="py-5">
        <div class="container">
            <h3 class="text-center mb-4 fw-semibold">Onze Producten</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow rounded-4">
                        <img src="images/Mes.jpg" class="card-img-top" alt="Koksmes">
                        <div class="card-body">
                            <h5 class="card-title">Koksmes</h5>
                            <p class="card-text">Perfect geslepen voor nauwkeurig snijden en hakken. Gemaakt van hoogwaardig staal voor langdurige scherpte.</p>
                            <a href="PHP/Producten.php" class="btn btn-primary rounded-pill px-4 fw-medium">Meer informatie</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow rounded-4">
                        <img src="images/Pan.jpg" class="card-img-top" alt="Anti-aanbakpan">
                        <div class="card-body">
                            <h5 class="card-title">Anti-aanbakpan</h5>
                            <p class="card-text">Kook moeiteloos met onze duurzame anti-aanbakpannen. Ideaal voor dagelijks gebruik en eenvoudig schoon te maken.</p>
                            <a href="PHP/Producten.php" class="btn btn-primary rounded-pill px-4 fw-medium">Meer informatie</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow rounded-4">
                        <img src="images/Plank.jpg" class="card-img-top" alt="Snijplank">
                        <div class="card-body">
                            <h5 class="card-title">Snijplank</h5>
                            <p class="card-text">Hoogwaardige planken voor al je snijwerk. Hygiënisch, duurzaam en stijlvol in elke keuken.</p>
                            <a href="PHP/Producten.php" class="btn btn-primary rounded-pill px-4 fw-medium">Meer informatie</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4 fw-semibold">Over Ons</h3>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="text-center fs-5">
                        Bij Chef's Choice zijn we gepassioneerd over het leveren van de beste keukentools aan chefs en thuiskoks. 
                        Onze producten zijn ontworpen met kwaliteit en functionaliteit in gedachten, zodat jij het beste uit je kookervaring haalt. 
                        Ontdek onze collectie en laat je inspireren door onze passie voor koken!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="chefs-corner" class="py-5 position-relative" style="background: linear-gradient(to bottom, #e3eafc 80%, transparent 100%); overflow: hidden;">
        <div class="chefs-corner-bg position-absolute top-50 end-0 translate-middle-y d-none d-lg-block" style="width: 600px; height: 600px; right: -300px; top: 50%; z-index: 0;">
            <div style="width: 100%; height: 100%; border-radius: 600px 0 0 600px / 600px 0 0 600px; background: #c7d3ea; opacity: 0.7;"></div>
        </div>
        <div class="container position-relative" style="z-index: 1;">
            <h3 class="text-center mb-4 fw-semibold text-primary"><i class="bi bi-people-fill me-2"></i>Chef's Corner</h3>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow rounded-4 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold mb-3"><i class="bi bi-lightbulb me-2"></i>Kooktip van de Chef</h5>
                            <p class="card-text fs-5 mb-0">
                                "Gebruik altijd een scherp mes! Een scherp mes is veiliger dan een bot mes en zorgt voor mooiere snijresultaten. Maak je messen regelmatig schoon en droog ze direct af om ze in topconditie te houden."
                            </p>
                        </div>
                    </div>
                    <div class="card border-0 shadow rounded-4 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold mb-3"><i class="bi bi-star me-2"></i>Uitgelichte Review</h5>
                            <blockquote class="blockquote mb-0">
                                <p class="fs-5">“Fantastische producten! Mijn nieuwe pan bakt perfect en de levering was snel. Aanrader!”</p>
                                <footer class="blockquote-footer">Sophie uit Utrecht</footer>
                            </blockquote>
                        </div>
                    </div>
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary fw-bold mb-3"><i class="bi bi-book me-2"></i>Recept van de Maand</h5>
                            <p class="mb-2 fw-semibold">Romige Risotto met Paddenstoelen</p>
                            <ul class="mb-2">
                                <li>300g risottorijst</li>
                                <li>250g gemengde paddenstoelen</li>
                                <li>1 ui, 2 teentjes knoflook</li>
                                <li>1 liter groentebouillon</li>
                                <li>Parmezaanse kaas, verse peterselie</li>
                            </ul>
                            <p class="mb-0">Bak de ui en knoflook glazig, voeg de rijst toe en blus af met bouillon. Voeg de paddenstoelen toe en roer tot de rijst romig is. Serveer met kaas en peterselie!</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-flex align-items-center justify-content-center mt-4 mt-lg-0">
                    <img src="images/kok.webp" alt="Chef-kok" class="img-fluid rounded-4 shadow-lg" style="max-height: 650px; object-fit: cover; width: 100%; max-width: 340px; position: relative; z-index: 2;">
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-5 bg-white">
        <div class="container">
            <h3 class="text-center mb-4 fw-semibold">Contact</h3>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="name" placeholder="Naam">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="E-mailadres">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Bericht</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Bericht"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">Verstuur bericht</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Chef's Choice. Alle rechten voorbehouden.</p>
            <p>Alle prijzen inclusief BTW</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>