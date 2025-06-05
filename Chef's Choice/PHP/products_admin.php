<?php
// products_admin.php
session_start();

// Simpele admin check (vervang dit met echte authenticatie in productie)
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: Index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producten Beheer - Chef's Choice</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <header class="bg-dark text-white py-4 shadow-sm">
        <div class="container">
            <h1 class="text-center mb-2">Producten Beheer</h1>
        </div>
    </header>
    <div class="container py-5">
        <h2 class="mb-4">Beheer Producten</h2>
        <div class="mb-3">
            <a href="Index.php" class="btn btn-secondary">Terug naar Home</a>
        </div>
        <!-- Producten overzicht (dummy data) -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Productnaam</th>
                    <th>Beschrijving</th>
                    <th>Acties</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Koksmes</td>
                    <td>Perfect geslepen voor nauwkeurig snijden en hakken.</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Bewerken</button>
                        <button class="btn btn-sm btn-danger">Verwijderen</button>
                    </td>
                </tr>
                <tr>
                    <td>Anti-aanbakpan</td>
                    <td>Duurzame anti-aanbakpan, ideaal voor dagelijks gebruik.</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Bewerken</button>
                        <button class="btn btn-sm btn-danger">Verwijderen</button>
                    </td>
                </tr>
                <tr>
                    <td>Snijplank</td>
                    <td>Hygiënisch, duurzaam en stijlvol in elke keuken.</td>
                    <td>
                        <button class="btn btn-sm btn-warning">Bewerken</button>
                        <button class="btn btn-sm btn-danger">Verwijderen</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="mt-4">
            <h4>Nieuw product toevoegen</h4>
            <form>
                <div class="mb-3">
                    <label for="productnaam" class="form-label">Productnaam</label>
                    <input type="text" class="form-control" id="productnaam" name="productnaam">
                </div>
                <div class="mb-3">
                    <label for="beschrijving" class="form-label">Beschrijving</label>
                    <textarea class="form-control" id="beschrijving" name="beschrijving" rows="2"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Toevoegen</button>
            </form>
        </div>
    </div>
    <footer class="text-white text-center py-3 bg-dark mt-5">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Chef's Choice. Alle rechten voorbehouden.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
