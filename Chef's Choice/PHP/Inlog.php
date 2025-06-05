<?php 
include_once 'DB_con.php';
session_start();

// Als al ingelogd, direct naar account
if (isset($_SESSION['user_id'])) {
    header('Location: Account.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM gebruikers WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($wachtwoord, $user['wachtwoord'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../Index.php');
        exit;
    } else {
        $error = 'Ongeldige combinatie van e-mail en wachtwoord.';
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    <?php include_once __DIR__ . '/header.php'; ?>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <h2 class="mb-4 text-center fw-bold">Inloggen</h2>
                        <?php if ($error): ?>
                            <div class="alert alert-danger text-center"> <?php echo $error; ?> </div>
                        <?php endif; ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required autofocus>
                            </div>
                            <div class="mb-3">
                                <label for="wachtwoord" class="form-label">Wachtwoord</label>
                                <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required>
                            </div>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium w-100 mb-2">Inloggen</button>
                        </form>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="../Index.php" class="link-secondary"><i class="bi bi-arrow-left"></i> Terug naar Home</a>
                            <a href="./Registratie.php" class="link-primary">Registreren</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>