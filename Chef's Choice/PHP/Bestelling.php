<?php
session_start();
include_once 'DB_con.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: Inlog.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Simpele winkelwagen in session (voorbeeld)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$cart = $_SESSION['cart'];

// Kortingscodes ophalen uit de database
$kortingscode_gebruikt = '';
$kortings_error = '';
$korting = 0;

if (isset($_POST['kortingscode_toepassen'])) {
    $code = strtoupper(trim($_POST['kortingscode'] ?? ''));
    $stmt = $pdo->prepare('SELECT * FROM kortingscodes WHERE code = ? AND actief = 1');
    $stmt->execute([$code]);
    $kortingsrow = $stmt->fetch();
    if ($kortingsrow) {
        $korting = (float)$kortingsrow['percentage'] / 100;
        $kortingscode_gebruikt = $code;
        $_SESSION['kortingscode'] = $code;
    } else {
        $kortings_error = 'Ongeldige of verlopen kortingscode.';
    }
}
if (isset($_SESSION['kortingscode'])) {
    $code = $_SESSION['kortingscode'];
    $stmt = $pdo->prepare('SELECT * FROM kortingscodes WHERE code = ? AND actief = 1');
    $stmt->execute([$code]);
    $kortingsrow = $stmt->fetch();
    if ($kortingsrow) {
        $korting = (float)$kortingsrow['percentage'] / 100;
        $kortingscode_gebruikt = $code;
    }
}

// Productgegevens ophalen voor producten in winkelwagen
$producten = [];
$totaal = 0;
if ($cart) {
    $ids = implode(',', array_map('intval', array_keys($cart)));
    $stmt = $pdo->query("SELECT * FROM producten WHERE id IN ($ids)");
    while ($row = $stmt->fetch()) {
        $row['gekozen_aantal'] = $cart[$row['id']];
        $row['totaalprijs'] = $row['prijs'] * $row['gekozen_aantal'];
        $producten[] = $row;
        $totaal += $row['totaalprijs'];
    }
}

// Korting toepassen
$totaal_met_korting = $totaal;
if ($korting > 0) {
    $totaal_met_korting = $totaal * (1 - $korting);
}

// Bestelling plaatsen
$success = '';
if (isset($_POST['bestellen'])) {
    if (!$producten) {
        $success = 'Je winkelwagen is leeg.';
    } else {
        foreach ($producten as $product) {
            $stmt = $pdo->prepare('INSERT INTO bestellingen (gebruiker_id, product_id, aantal, datum_besteld) VALUES (?, ?, ?, NOW())');
            $stmt->execute([$user_id, $product['id'], $product['gekozen_aantal']]);
            // Voorraad bijwerken
            $stmt = $pdo->prepare('UPDATE producten SET aantal = aantal - ? WHERE id = ?');
            $stmt->execute([$product['gekozen_aantal'], $product['id']]);
        }
        $_SESSION['cart'] = [];
        unset($_SESSION['kortingscode']);
        $success = 'Bestelling succesvol geplaatst!';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bestelling Overzicht | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Bestelling Overzicht</h2>
            <a href="Producten.php" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">Verder winkelen</a>
        </div>
        <?php if ($success): ?>
            <div class="alert alert-success mb-4"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($producten): ?>
        <div class="table-responsive mb-4">
            <table class="table table-bordered align-middle shadow-sm bg-white rounded-4 overflow-hidden">
                <thead class="table-primary">
                    <tr>
                        <th class="text-center" style="width:120px;"><i class="bi bi-image"></i> Afbeelding</th>
                        <th class="text-center"><i class="bi bi-box-seam"></i> Product</th>
                        <th class="text-center"><i class="bi bi-currency-euro"></i> Prijs</th>
                        <th class="text-center"><i class="bi bi-hash"></i> Aantal</th>
                        <th class="text-center"><i class="bi bi-cash-coin"></i> Totaal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($producten as $product): ?>
                    <tr>
                        <td class="text-center align-middle bg-light"><img src="../images/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['naam']); ?>" style="max-width: 90px; max-height: 90px; object-fit: contain; display: block; margin: 0 auto;"></td>
                        <td class="fw-semibold text-primary align-middle">
                            <i class="bi bi-basket2"></i> <?php echo htmlspecialchars($product['naam']); ?>
                        </td>
                        <td class="text-end align-middle">&euro;<?php echo number_format($product['prijs'], 2, ',', '.'); ?></td>
                        <td class="text-center align-middle">
                            <span class="badge bg-info text-dark fs-6 px-3 py-2 shadow-sm"><?php echo $product['gekozen_aantal']; ?></span>
                        </td>
                        <td class="text-end align-middle fw-semibold text-success">&euro;<?php echo number_format($product['totaalprijs'], 2, ',', '.'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Subtotaal</th>
                        <th class="text-end">&euro;<?php echo number_format($totaal, 2, ',', '.'); ?></th>
                    </tr>
                    <?php if ($korting > 0): ?>
                    <tr>
                        <th colspan="3" class="text-end text-success">Korting (<?php echo htmlspecialchars($kortingscode_gebruikt); ?>)</th>
                        <th class="text-end text-success">- &euro;<?php echo number_format($totaal - $totaal_met_korting, 2, ',', '.'); ?></th>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th colspan="3" class="text-end fs-5">Totaal</th>
                        <th class="text-end fs-5 text-primary">&euro;<?php echo number_format($totaal_met_korting, 2, ',', '.'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <form method="post" class="mb-4">
            <div class="row g-2 align-items-center mb-3">
                <div class="col-auto">
                    <input type="text" class="form-control shadow-sm" name="kortingscode" placeholder="Kortingscode invoeren" value="<?php echo htmlspecialchars($kortingscode_gebruikt); ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" name="kortingscode_toepassen" class="btn btn-outline-info rounded-pill px-4 fw-medium shadow">Toepassen</button>
                </div>
                <div class="col-auto">
                    <?php if ($kortings_error): ?><span class="text-danger ms-2 fw-semibold"><?php echo $kortings_error; ?></span><?php endif; ?>
                </div>
            </div>
            <button type="submit" name="bestellen" class="btn btn-success rounded-pill px-4 fw-medium shadow"><i class="bi bi-bag-check"></i> Bestelling plaatsen</button>
        </form>
        <?php else: ?>
            <div class="alert alert-info">Je winkelwagen is leeg. <a href="Producten.php">Verder winkelen</a></div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
