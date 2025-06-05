<?php
session_start();
include_once 'DB_con.php';

// Reviews ophalen
$stmt = $pdo->prepare('SELECT r.*, p.naam AS product_naam, g.naam AS gebruiker_naam FROM reviews r JOIN producten p ON r.product_id = p.id JOIN gebruikers g ON r.gebruiker_id = g.id ORDER BY r.datum DESC');
$stmt->execute();
$reviews = $stmt->fetchAll();

// Producten ophalen voor reviewformulier
$stmt = $pdo->query('SELECT id, naam FROM producten ORDER BY naam');
$producten = $stmt->fetchAll();

// Review toevoegen
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $product_id = (int)($_POST['product_id'] ?? 0);
    $beoordeling = (int)($_POST['beoordeling'] ?? 0);
    $tekst = trim($_POST['tekst'] ?? '');
    if ($product_id && $beoordeling >= 1 && $beoordeling <= 5 && $tekst) {
        $stmt = $pdo->prepare('INSERT INTO reviews (product_id, gebruiker_id, beoordeling, tekst, datum) VALUES (?, ?, ?, ?, NOW())');
        if ($stmt->execute([$product_id, $_SESSION['user_id'], $beoordeling, $tekst])) {
            header('Location: Reviews.php');
            exit;
        } else {
            $error = 'Fout bij plaatsen van review.';
        }
    } else {
        $error = 'Vul alle velden correct in.';
    }
}

// Review verwijderen door admin
if (isset($_POST['delete_review']) && isset($_SESSION['user_id'])) {
    // Check of gebruiker admin is
    $stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if ($user && $user['role'] === 'admin') {
        $review_id = (int)($_POST['review_id'] ?? 0);
        if ($review_id > 0) {
            $stmt = $pdo->prepare('DELETE FROM reviews WHERE id = ?');
            $stmt->execute([$review_id]);
            $success = 'Review verwijderd!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productreviews | Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <?php include_once __DIR__ . '/header.php'; ?>
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 text-primary fw-bold"><i class="bi bi-star-half me-2"></i>Productreviews</h2>
        <a href="../Index.php" class="btn btn-outline-primary rounded-pill px-4 fw-medium shadow-sm">Home</a>
    </div>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="card p-4 mb-4 shadow border-0 bg-light">
        <h5 class="mb-3 fw-semibold text-primary"><i class="bi bi-pencil-square me-2"></i>Schrijf een review</h5>
        <?php if ($error): ?><div class="alert alert-danger"> <?php echo $error; ?> </div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"> <?php echo $success; ?> </div><?php endif; ?>
        <form method="post" class="row g-3">
            <div class="col-md-4">
                <label for="product_id" class="form-label">Product</label>
                <select name="product_id" id="product_id" class="form-select shadow-sm" required>
                    <option value="">Kies een product</option>
                    <?php foreach ($producten as $p): ?>
                        <option value="<?php echo $p['id']; ?>"><?php echo htmlspecialchars($p['naam']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="beoordeling" class="form-label">Beoordeling</label>
                <select name="beoordeling" id="beoordeling" class="form-select shadow-sm" required>
                    <option value="">-</option>
                    <?php for ($i=1; $i<=5; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> ster<?php if($i>1) echo 'ren'; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="tekst" class="form-label">Review</label>
                <textarea name="tekst" id="tekst" class="form-control shadow-sm" rows="2" required placeholder="Wat vond je van het product?"></textarea>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium shadow">Plaatsen</button>
            </div>
        </form>
    </div>
    <?php else: ?>
        <div class="alert alert-info mb-4 shadow-sm">Log in om een review te plaatsen.</div>
    <?php endif; ?>
    <h5 class="fw-semibold mb-3 text-primary"><i class="bi bi-chat-left-text me-2"></i>Alle reviews</h5>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle shadow-sm bg-white rounded-4 overflow-hidden">
            <thead class="table-primary">
                <tr>
                    <th>Product</th>
                    <th>Gebruiker</th>
                    <th>Beoordeling</th>
                    <th>Review</th>
                    <th>Datum</th>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php 
                        $stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
                        $stmt->execute([$_SESSION['user_id']]);
                        $user = $stmt->fetch();
                        if ($user && $user['role'] === 'admin'): ?>
                        <th>Actie</th>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($reviews as $r): ?>
<tr>
    <td class="fw-semibold text-primary"><?php echo htmlspecialchars($r['product_naam']); ?></td>
    <td><?php echo htmlspecialchars($r['gebruiker_naam']); ?></td>
    <td class="text-warning fs-5"><?php echo str_repeat('★', $r['beoordeling']) . str_repeat('☆', 5-$r['beoordeling']); ?></td>
    <td><?php echo nl2br(htmlspecialchars($r['tekst'])); ?></td>
    <td class="text-muted small"><?php echo date('d-m-Y H:i', strtotime($r['datum'])); ?></td>
    <?php if (isset($_SESSION['user_id'])): ?>
        <?php 
        $stmt = $pdo->prepare('SELECT role FROM gebruikers WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        if ($user && $user['role'] === 'admin'): ?>
        <td>
            <form method="post" style="display:inline-block">
                <input type="hidden" name="review_id" value="<?php echo $r['id']; ?>">
                <button type="submit" name="delete_review" class="btn btn-sm btn-danger rounded-pill px-3 shadow" onclick="return confirm('Weet je zeker dat je deze review wilt verwijderen?');">
                    <i class="bi bi-trash"></i> Verwijder
                </button>
            </form>
        </td>
        <?php endif; ?>
    <?php endif; ?>
</tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
