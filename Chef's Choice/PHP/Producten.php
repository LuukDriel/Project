<?php
include_once 'DB_con.php';
// Producten ophalen
$stmt = $pdo->prepare("SELECT * FROM producten");
$stmt->execute();
$result = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Producten - Chef's Choice">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Producten | Chef's Choice</title>
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>

    <section class="py-5">
        <div class="container">
            <h3 class="text-center mb-4 fw-semibold">Producten</h3>
            <div class="row g-4">
                <?php if ($result && count($result) > 0): ?>
                    <?php foreach($result as $row): ?>
                        <div class="col-md-4">
                            <div class="card h-100 shadow rounded-4">
                                <img src="../images/<?php echo htmlspecialchars($row['img']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['naam']); ?>">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['naam']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($row['beschrijving']); ?></p>
                                    <div class="mb-2 text-muted small">Voorraad: <?php echo (int)$row['aantal']; ?></div>
                                    <div class="mt-auto d-flex justify-content-between align-items-center">
                                        <span class="fw-bold fs-5">&euro;<?php echo number_format($row['prijs'], 2, ',', '.'); ?></span>
                                        <a href="Bestel.php?id=<?php echo $row['id']; ?>" class="btn btn-primary rounded-pill px-4 fw-medium" <?php if($row['aantal'] < 1) echo 'disabled'; ?>>Bestellen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">Geen producten gevonden.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Chef's Choice. Alle rechten voorbehouden.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
