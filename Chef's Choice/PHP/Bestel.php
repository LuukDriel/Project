<?php
session_start();
include_once 'DB_con.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: Inlog.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Product ophalen via GET
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    header('Location: Producten.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM producten WHERE id = ?');
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: Producten.php');
    exit;
}

$success = '';
$error = '';

// Bestelling verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aantal = isset($_POST['aantal']) ? (int)$_POST['aantal'] : 1;
    if ($aantal < 1) $aantal = 1;
    if ($aantal > $product['aantal']) {
        $error = 'Niet genoeg voorraad beschikbaar.';
    } else {
        // Voeg toe aan winkelwagen (session)
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $aantal;
        } else {
            $_SESSION['cart'][$product_id] = $aantal;
        }
        header('Location: Bestelling.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestel <?php echo htmlspecialchars($product['naam']); ?> | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Bestel <?php echo htmlspecialchars($product['naam']); ?></h2>
            <a href="Producten.php" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">Terug naar producten</a>
        </div>
        <div class="row">
            <div class="col-md-5 d-flex flex-column align-items-center justify-content-start">
                <img src="../images/<?php echo htmlspecialchars($product['img']); ?>" class="img-fluid rounded shadow-sm mb-3" alt="<?php echo htmlspecialchars($product['naam']); ?>" style="max-width: 320px; width: 100%; height: auto;">
            </div>
            <div class="col-md-7 d-flex flex-column justify-content-center">
                <div class="card p-4 mb-3">
                    <h4 class="text-primary mb-3" style="font-size: 2rem;"><?php echo htmlspecialchars($product['naam']); ?></h4>
                    <p class="mb-1">Prijs: <strong>&euro;<?php echo number_format($product['prijs'], 2, ',', '.'); ?></strong></p>
                    <p class="mb-1">Voorraad: <?php echo $product['aantal']; ?></p>
                    <p><?php echo htmlspecialchars($product['beschrijving']); ?></p>
                    <?php if ($success): ?>
                        <div class="alert alert-success"> <?php echo $success; ?> </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger"> <?php echo $error; ?> </div>
                    <?php endif; ?>
                    <form method="post" class="mt-3">
                        <div class="mb-3">
                            <label for="aantal" class="form-label">Aantal</label>
                            <input type="number" class="form-control" id="aantal" name="aantal" min="1" max="<?php echo $product['aantal']; ?>" value="1" <?php if ($product['aantal'] < 1) echo 'disabled'; ?> required oninput="updateTotaalPrijs()">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Totaalprijs</label>
                            <input type="text" class="form-control" id="totaalprijs" value="&euro;<?php echo number_format($product['prijs'], 2, ',', '.'); ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-medium" <?php if ($product['aantal'] < 1) echo 'disabled'; ?>>Toevoegen aan winkelwagen</button>
                    </form>
                    <script>
                    function updateTotaalPrijs() {
                        var prijs = <?php echo str_replace(',', '.', $product['prijs']); ?>;
                        var aantal = document.getElementById('aantal').value;
                        var totaal = prijs * aantal;
                        document.getElementById('totaalprijs').value = '€' + totaal.toFixed(2).replace('.', ',');
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
