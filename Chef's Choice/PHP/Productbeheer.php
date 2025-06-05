<?php
session_start();
include_once 'DB_con.php';

// Check of gebruiker is admin
if (!isset($_SESSION['user_id'])) {
    header('Location: Inlog.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
if (!$user || $user['role'] !== 'admin') {
    header('Location: ../Index.php');
    exit;
}

// Product toevoegen
$product_success = '';
$product_error = '';
if (isset($_POST['add_product'])) {
    $naam = trim($_POST['naam'] ?? '');
    $prijs = floatval($_POST['prijs'] ?? 0);
    $aantal = intval($_POST['aantal'] ?? 0);
    $beschrijving = trim($_POST['beschrijving'] ?? '');
    $img = trim($_POST['img'] ?? '');
    if ($naam && $prijs > 0 && $aantal >= 0 && $img) {
        $stmt = $pdo->prepare('INSERT INTO producten (naam, prijs, aantal, beschrijving, img) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$naam, $prijs, $aantal, $beschrijving, $img]);
        $product_success = 'Product toegevoegd!';
    } else {
        $product_error = 'Vul alle verplichte velden correct in.';
    }
}

// Product verwijderen
if (isset($_POST['delete_product'])) {
    $pid = intval($_POST['product_id']);
    $stmt = $pdo->prepare('DELETE FROM producten WHERE id = ?');
    $stmt->execute([$pid]);
    $product_success = 'Product verwijderd!';
}

// Producten ophalen
$stmt = $pdo->query('SELECT * FROM producten ORDER BY id DESC');
$producten = $stmt->fetchAll();

// Rollen beheren
$role_success = '';
$role_error = '';
if (isset($_POST['change_role'])) {
    $uid = intval($_POST['user_id']);
    $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
    $stmt = $pdo->prepare('UPDATE gebruikers SET role = ? WHERE id = ?');
    $stmt->execute([$role, $uid]);
    $role_success = 'Rol bijgewerkt!';
}
// Gebruikers ophalen
$stmt = $pdo->query('SELECT id, naam, email, role FROM gebruikers ORDER BY id');
$gebruikers = $stmt->fetchAll();

// Kortingscode toevoegen
$kortings_success = '';
$kortings_error = '';
if (isset($_POST['add_kortingscode'])) {
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $percentage = floatval($_POST['percentage'] ?? 0);
    $actief = isset($_POST['actief']) ? 1 : 0;
    if ($code && $percentage > 0 && $percentage <= 100) {
        $stmt = $pdo->prepare('INSERT INTO kortingscodes (code, percentage, actief) VALUES (?, ?, ?)');
        $stmt->execute([$code, $percentage, $actief]);
        $kortings_success = 'Kortingscode toegevoegd!';
    } else {
        $kortings_error = 'Vul een geldige code en percentage in (1-100).';
    }
}
// Kortingscode verwijderen
if (isset($_POST['delete_kortingscode'])) {
    $kid = intval($_POST['kortingscode_id']);
    $stmt = $pdo->prepare('DELETE FROM kortingscodes WHERE id = ?');
    $stmt->execute([$kid]);
    $kortings_success = 'Kortingscode verwijderd!';
}
// Kortingscode aanpassen
if (isset($_POST['edit_kortingscode'])) {
    $kid = intval($_POST['kortingscode_id']);
    $code = strtoupper(trim($_POST['code'] ?? ''));
    $percentage = floatval($_POST['percentage'] ?? 0);
    $actief = isset($_POST['actief']) ? 1 : 0;
    if ($code && $percentage > 0 && $percentage <= 100) {
        $stmt = $pdo->prepare('UPDATE kortingscodes SET code = ?, percentage = ?, actief = ? WHERE id = ?');
        $stmt->execute([$code, $percentage, $actief, $kid]);
        $kortings_success = 'Kortingscode bijgewerkt!';
    } else {
        $kortings_error = 'Vul een geldige code en percentage in (1-100).';
    }
}
// Kortingscodes ophalen
$kortingscodes = $pdo->query('SELECT * FROM kortingscodes ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productbeheer | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Productbeheer</h2>
        <a href="../Index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-medium">Home</a>
    </div>
    <?php if ($product_success): ?><div class="alert alert-success"> <?php echo $product_success; ?> </div><?php endif; ?>
    <?php if ($product_error): ?><div class="alert alert-danger"> <?php echo $product_error; ?> </div><?php endif; ?>
    <div class="card p-4 mb-4">
        <h5>Nieuw product toevoegen</h5>
        <form method="post" class="row g-3">
            <div class="col-md-3"><input type="text" name="naam" class="form-control" placeholder="Naam*" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="prijs" class="form-control" placeholder="Prijs*" required></div>
            <div class="col-md-2"><input type="number" name="aantal" class="form-control" placeholder="Voorraad*" required></div>
            <div class="col-md-3"><input type="text" name="img" class="form-control" placeholder="Afbeelding (bestandsnaam)*" required></div>
            <div class="col-md-12"><textarea name="beschrijving" class="form-control" placeholder="Beschrijving"></textarea></div>
            <div class="col-md-12"><button type="submit" name="add_product" class="btn btn-success">Toevoegen</button></div>
        </form>
    </div>
    <h5>Productenlijst</h5>
    <div class="table-responsive mb-5">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>Naam</th><th>Prijs</th><th>Voorraad</th><th>Afbeelding</th><th>Acties</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($producten as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo htmlspecialchars($p['naam']); ?></td>
                    <td>&euro;<?php echo number_format($p['prijs'], 2, ',', '.'); ?></td>
                    <td><?php echo $p['aantal']; ?></td>
                    <td><?php echo htmlspecialchars($p['img']); ?></td>
                    <td>
                        <form method="post" style="display:inline-block">
                            <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                            <button type="submit" name="delete_product" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">Verwijderen</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h5>Gebruikersrollen beheren</h5>
    <?php if ($role_success): ?><div class="alert alert-success"> <?php echo $role_success; ?> </div><?php endif; ?>
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>ID</th><th>Naam</th><th>E-mail</th><th>Rol</th><th>Actie</th></tr>
            </thead>
            <tbody>
            <?php foreach ($gebruikers as $g): ?>
                <tr>
                    <td><?php echo $g['id']; ?></td>
                    <td><?php echo htmlspecialchars($g['naam']); ?></td>
                    <td><?php echo htmlspecialchars($g['email']); ?></td>
                    <td><?php echo $g['role']; ?></td>
                    <td>
                        <form method="post" class="d-inline-flex align-items-center gap-2">
                            <input type="hidden" name="user_id" value="<?php echo $g['id']; ?>">
                            <select name="role" class="form-select form-select-sm">
                                <option value="user" <?php if($g['role']==='user') echo 'selected'; ?>>user</option>
                                <option value="admin" <?php if($g['role']==='admin') echo 'selected'; ?>>admin</option>
                            </select>
                            <button type="submit" name="change_role" class="btn btn-primary btn-sm">Opslaan</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <h5>Kortingscodes beheren</h5>
    <?php if ($kortings_success): ?><div class="alert alert-success"> <?php echo $kortings_success; ?> </div><?php endif; ?>
    <?php if ($kortings_error): ?><div class="alert alert-danger"> <?php echo $kortings_error; ?> </div><?php endif; ?>
    <div class="card p-4 mb-4">
        <h6>Nieuwe kortingscode toevoegen</h6>
        <form method="post" class="row g-3 align-items-end">
            <div class="col-md-3"><input type="text" name="code" class="form-control" placeholder="Code*" required></div>
            <div class="col-md-2"><input type="number" step="0.01" name="percentage" class="form-control" placeholder="% Korting*" required></div>
            <div class="col-md-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="actief" id="actief_nieuw" checked>
                    <label class="form-check-label" for="actief_nieuw">Actief</label>
                </div>
            </div>
            <div class="col-md-2"><button type="submit" name="add_kortingscode" class="btn btn-success">Toevoegen</button></div>
        </form>
    </div>
    <div class="table-responsive mb-5">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr><th>ID</th><th>Code</th><th>Percentage</th><th>Actief</th><th>Acties</th></tr>
            </thead>
            <tbody>
            <?php foreach ($kortingscodes as $kc): ?>
                <tr>
                    <form method="post" class="row g-1 align-items-center">
                        <td class="col-auto"><?php echo $kc['id']; ?></td>
                        <td class="col-auto"><input type="text" name="code" value="<?php echo htmlspecialchars($kc['code']); ?>" class="form-control form-control-sm" required></td>
                        <td class="col-auto"><input type="number" step="0.01" name="percentage" value="<?php echo $kc['percentage']; ?>" class="form-control form-control-sm" required></td>
                        <td class="col-auto text-center">
                            <input class="form-check-input" type="checkbox" name="actief" <?php if($kc['actief']) echo 'checked'; ?>>
                        </td>
                        <td class="col-auto">
                            <input type="hidden" name="kortingscode_id" value="<?php echo $kc['id']; ?>">
                            <button type="submit" name="edit_kortingscode" class="btn btn-primary btn-sm">Opslaan</button>
                            <button type="submit" name="delete_kortingscode" class="btn btn-danger btn-sm" onclick="return confirm('Weet je zeker dat je deze kortingscode wilt verwijderen?');">Verwijderen</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
