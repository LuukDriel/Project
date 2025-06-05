<?php 
include_once 'DB_con.php';
session_start();

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    if (!$naam || !$email || !$wachtwoord) {
        $error = 'Vul alle velden in.';
    } else {
        // Check of e-mail al bestaat
        $stmt = $pdo->prepare('SELECT id FROM gebruikers WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Dit e-mailadres is al geregistreerd.';
        } else {
            $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO gebruikers (naam, email, wachtwoord) VALUES (?, ?, ?)');
            if ($stmt->execute([$naam, $email, $hash])) {
                $success = 'Registratie gelukt! Je kunt nu inloggen.';
            } else {
                $error = 'Registratie mislukt. Probeer het opnieuw.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center fw-bold">Registreren</h2>
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success text-center"> <?php echo $success; ?> <a href="Inlog.php" class="btn btn-link">Inloggen</a></div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="naam" class="form-label">Naam</label>
                                <input type="text" class="form-control" id="naam" name="naam" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="wachtwoord" class="form-label">Wachtwoord</label>
                                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium w-100 mb-2">Registreren</button>
                        </form>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="../Index.php" class="link-secondary"><i class="bi bi-arrow-left"></i> Terug naar Home</a>
                            <a href="Inlog.php" class="link-primary">Inloggen</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
