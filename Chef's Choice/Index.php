<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Chef's Choice - Dé plek voor hoogwaardige keukenproducten en culinaire inspiratie. Ontdek nieuwe smaken en culinaire hoogstandjes!">
    <meta name="keywords" content="recepten, koken, eten, culinair, makkelijke recepten, heerlijke recepten, Chef's Choice">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/style.css">
    <title>Chef's Choice</title>

</head>
<body>
    <header class="bg-dark text-white py-4 shadow-sm">
        <div class="container">
            <h1 class="text-center mb-2" style="font-weight:700; letter-spacing:1px;">Chef's Choice</h1>
            <p class="text-center mb-0" style="font-size:1.15rem;">Welkom bij Chef's Choice - Dé plek voor hoogwaardige keukenproducten en culinaire inspiratie.</p>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">Chef's Choice</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Navigatie wisselen">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Producten</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Over Ons</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="hero" class="text-center text-white py-5">
        <div class="container">
            <h2>Ontdek de Beste Keukentools</h2>
            <p>Verbeter je kookervaring met onze premium keukenproducten.</p>
            <a href="#products" class="btn btn-light btn-lg mt-3 shadow-sm">Bekijk Producten</a>
        </div>
    </section>

    <section id="products" class="py-5">
        <div class="container">
            <h3 class="text-center mb-4" style="font-weight:600;">Onze Producten</h3>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="images/knife.jpg" class="card-img-top" alt="Koksmes">
                        <div class="card-body">
                            <h5 class="card-title">Koksmes</h5>
                            <p class="card-text">Perfect geslepen voor nauwkeurig snijden en hakken. Gemaakt van hoogwaardig staal voor langdurige scherpte.</p>
                            <a href="#" class="btn btn-primary">Meer informatie</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="images/pan.jpg" class="card-img-top" alt="Anti-aanbakpan">
                        <div class="card-body">
                            <h5 class="card-title">Anti-aanbakpan</h5>
                            <p class="card-text">Kook moeiteloos met onze duurzame anti-aanbakpannen. Ideaal voor dagelijks gebruik en eenvoudig schoon te maken.</p>
                            <a href="#" class="btn btn-primary">Meer informatie</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="images/cutting-board.jpg" class="card-img-top" alt="Snijplank">
                        <div class="card-body">
                            <h5 class="card-title">Snijplank</h5>
                            <p class="card-text">Hoogwaardige planken voor al je snijwerk. Hygiënisch, duurzaam en stijlvol in elke keuken.</p>
                            <a href="#" class="btn btn-primary">Meer informatie</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5">
        <div class="container">
            <h3 class="text-center mb-4" style="font-weight:600;">Over Ons</h3>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="text-center" style="font-size:1.15rem;">
                        Bij Chef's Choice zijn we gepassioneerd over het leveren van de beste keukentools aan chefs en thuiskoks. 
                        Onze producten zijn ontworpen met kwaliteit en functionaliteit in gedachten, zodat jij het beste uit je kookervaring haalt. 
                        Ontdek onze collectie en laat je inspireren door onze passie voor koken!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-5 bg-white">
        <div class="container">
            <h3 class="text-center mb-4" style="font-weight:600;">Contact</h3>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control" id="name" placeholder="Uw naam">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" placeholder="Uw e-mailadres">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Bericht</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Uw bericht"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">Verstuur bericht</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Chef's Choice. Alle rechten voorbehouden.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
