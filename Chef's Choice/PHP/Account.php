<?php
session_start();
include_once 'DB_con.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    header('Location: Inlog.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Gebruikersgegevens ophalen
$stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Bestellingen ophalen
$stmt2 = $pdo->prepare('SELECT b.id, p.naam, p.prijs, b.aantal, b.datum_besteld FROM bestellingen b JOIN producten p ON b.product_id = p.id WHERE b.gebruiker_id = ? ORDER BY b.datum_besteld DESC');
$stmt2->execute([$user_id]);
$orders = $stmt2->fetchAll();

// Update of verwijder actie verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $naam = $_POST['naam'] ?? '';
        $email = $_POST['email'] ?? '';
        $stmt = $pdo->prepare('UPDATE gebruikers SET naam = ?, email = ? WHERE id = ?');
        $stmt->execute([$naam, $email, $user_id]);
        header('Location: Account.php?updated=1');
        exit;
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare('DELETE FROM gebruikers WHERE id = ?');
        $stmt->execute([$user_id]);
        // Eventueel uitloggen en redirecten
        header('Location: ../Index.php?account_deleted=1');
        exit;
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $password_error = '';
        $password_success = '';
        // Controleer huidig wachtwoord
        if (!password_verify($current_password, $user['wachtwoord'])) {
            $password_error = 'Huidig wachtwoord is onjuist.';
        } elseif (strlen($new_password) < 6) {
            $password_error = 'Nieuw wachtwoord moet minimaal 6 tekens zijn.';
        } elseif ($new_password !== $confirm_password) {
            $password_error = 'Nieuwe wachtwoorden komen niet overeen.';
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE gebruikers SET wachtwoord = ? WHERE id = ?');
            $stmt->execute([$hashed, $user_id]);
            $password_success = 'Wachtwoord succesvol gewijzigd!';
            // Refresh user data
            $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE id = ?');
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn Account | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Mijn Account</h2>
            <a href="../Index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">Home</a>
        </div>
        <?php if ($user): ?>
        <form method="post" class="mb-4 p-4 bg-light rounded shadow-sm">
            <div class="mb-3">
                <label for="naam" class="form-label">Naam</label>
                <input type="text" class="form-control" id="naam" name="naam" value="<?php echo isset($user['naam']) ? htmlspecialchars($user['naam']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary rounded-pill px-4 fw-medium">Gegevens bijwerken</button>
            <button type="submit" name="delete" class="btn btn-danger rounded-pill px-4 fw-medium ms-2" onclick="return confirm('Weet je zeker dat je je account wilt verwijderen?');">Account verwijderen</button>
        </form>
        <!-- Wachtwoord wijzigen formulier -->
        <div class="mb-4 p-4 bg-light rounded shadow-sm">
            <h5 class="mb-3">Wachtwoord wijzigen</h5>
            <?php if (!empty($password_error)): ?>
                <div class="alert alert-danger"><?php echo $password_error; ?></div>
            <?php elseif (!empty($password_success)): ?>
                <div class="alert alert-success"><?php echo $password_success; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Huidig wachtwoord</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Nieuw wachtwoord</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Bevestig nieuw wachtwoord</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" name="change_password" class="btn btn-warning rounded-pill px-4 fw-medium">Wachtwoord wijzigen</button>
            </form>
        </div>
        <?php else: ?>
            <div class="alert alert-danger">Gebruiker niet gevonden.</div>
        <?php endif; ?>

        <h3 class="mb-3">Mijn Bestellingen</h3>
        <?php if ($orders && count($orders) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Aantal</th>
                        <th>Prijs</th>
                        <th>Datum</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['id']; ?></td>
                        <td><?php echo htmlspecialchars($order['naam']); ?></td>
                        <td><?php echo $order['aantal']; ?></td>
                        <td>&euro;<?php echo number_format($order['prijs'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($order['datum_besteld']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-info">Je hebt nog geen bestellingen geplaatst.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
