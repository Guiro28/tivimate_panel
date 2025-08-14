<?php
include('includes/header.php');

$jsonFile = __DIR__ . '/settings.json';

// Charger les données JSON ou valeurs par défaut
if (file_exists($jsonFile)) {
    $settings = json_decode(file_get_contents($jsonFile), true);
} else {
    $settings = [
        'buttons' => [
            'discord' => ['enabled' => true, 'url' => 'https://discord.gg/example'],
            'telegram' => ['enabled' => true, 'url' => 'https://t.me/example'],
            'contact' => ['enabled' => true, 'url' => 'mailto:contact@example.com'],
            'forum' => ['enabled' => true, 'url' => 'https://forum.example.com'],
        ],
        'news' => [
            ['date' => '2025-07-15', 'text' => 'Ajout de MT Manager dans les téléchargements'],
            ['date' => '2025-07-15', 'text' => 'Ajout d\'une page d\'accueil'],
            ['date' => '2025-07-14', 'text' => 'Ajout des backups et APKs Tivimate'],
        ],
        'downloads' => [
            ['name' => 'MT Manager', 'path' => 'downloads/MT_Manager.apk', 'note' => 'Pour modifier les APKs'],
            ['name' => 'Tivimate 5.1.0 RTX', 'path' => 'downloads/TiViMate[RTX][5.1.0]_rv5_sign.apk', 'note' => 'Code Downloader : 9999999'],
        ],
    ];
}

// Suppression actualité
if (isset($_GET['delete_news'])) {
    $index = (int)$_GET['delete_news'];
    if (isset($settings['news'][$index])) {
        array_splice($settings['news'], $index, 1);
        file_put_contents($jsonFile, json_encode($settings, JSON_PRETTY_PRINT));
        echo "<script>window.location.href='settings.php?status=deleted'</script>";
        exit;
    }
}

// Suppression téléchargement
if (isset($_GET['delete_download'])) {
    $index = (int)$_GET['delete_download'];
    if (isset($settings['downloads'][$index])) {
        array_splice($settings['downloads'], $index, 1);
        file_put_contents($jsonFile, json_encode($settings, JSON_PRETTY_PRINT));
        echo "<script>window.location.href='settings.php?status=deleted_download'</script>";
        exit;
    }
}

// Traitement formulaire
if (isset($_POST['submit'])) {
    // Boutons
    foreach ($settings['buttons'] as $key => $btn) {
        $settings['buttons'][$key]['enabled'] = isset($_POST['btn_enabled'][$key]) ? true : false;
        $settings['buttons'][$key]['url'] = trim($_POST['btn_url'][$key] ?? '');
    }

    // Actualités
    if (!empty($_POST['news_date']) && !empty($_POST['news_text'])) {
        foreach ($_POST['news_date'] as $i => $date) {
            $date = trim($date);
            $text = trim($_POST['news_text'][$i]);
            if ($date !== '' && $text !== '') {
                $settings['news'][$i] = ['date' => $date, 'text' => $text];
            }
        }
    }

    // Ajout actualité
    if (!empty($_POST['new_news_date']) && !empty($_POST['new_news_text'])) {
        $newDate = trim($_POST['new_news_date']);
        $newText = trim($_POST['new_news_text']);
        if ($newDate !== '' && $newText !== '') {
            $settings['news'][] = ['date' => $newDate, 'text' => $newText];
        }
    }

    // Téléchargements (modification)
    if (!empty($_POST['download_name']) && !empty($_POST['download_path']) && !empty($_POST['download_note'])) {
        foreach ($_POST['download_name'] as $i => $name) {
            $name = trim($name);
            $path = trim($_POST['download_path'][$i]);
            $note = trim($_POST['download_note'][$i]);
            if ($name !== '' && $path !== '' && $note !== '') {
                $settings['downloads'][$i] = ['name' => $name, 'path' => $path, 'note' => $note];
            }
        }
    }

    // Ajout téléchargement
    if (!empty($_POST['new_download_name']) && !empty($_POST['new_download_path']) && !empty($_POST['new_download_note'])) {
        $newName = trim($_POST['new_download_name']);
        $newPath = trim($_POST['new_download_path']);
        $newNote = trim($_POST['new_download_note']);
        if ($newName !== '' && $newPath !== '' && $newNote !== '') {
            $settings['downloads'][] = ['name' => $newName, 'path' => $newPath, 'note' => $newNote];
        }
    }

    // Sauvegarder tout dans JSON
    file_put_contents($jsonFile, json_encode($settings, JSON_PRETTY_PRINT));
    echo "<script>window.location.href='settings.php?status=1'</script>";
    exit;
}
?>

<style>
/* Ton style d’origine repris */

.ctmain-table {
    margin-top: 20px;
    padding: 0 15px;
}

.ctcard {
    background: linear-gradient(45deg, #1a1a2e 0%, #16213e 100%);
    border: none;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}

.card-header-warning {
    background: linear-gradient(45deg, #4C6EF5 0%, #6282FF 100%);
    padding: 20px;
    border: none;
}

.card-header-warning h2 {
    color: white;
    margin: 0;
    font-size: 1.5rem;
    font-weight: 500;
}

.card-header-warning i {
    margin-right: 10px;
}

.card-body {
    padding: 25px;
}

.form-control {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    color: #fff;
    padding: 12px 15px;
    transition: all 0.3s ease;
    margin-bottom: 15px;
}

.form-control:focus {
    background: rgba(255, 255, 255, 0.1);
    border-color: #4C6EF5;
    box-shadow: 0 0 0 2px rgba(76, 110, 245, 0.2);
    color: #fff;
}

.form-label {
    color: #a8b2d1;
    font-weight: 500;
    margin-bottom: 8px;
    display: block;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
}

.btn-info {
    background: linear-gradient(45deg, #4C6EF5 0%, #6282FF 100%);
    box-shadow: 0 4px 15px rgba(76, 110, 245, 0.2);
    color: white;
}

.btn-info:hover {
    background: linear-gradient(45deg, #3b5ef0 0%, #4f6fff 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(76, 110, 245, 0.3);
    color: white;
}

.btn-danger {
    background: linear-gradient(45deg, #FF3366 0%, #FF6B6B 100%);
    box-shadow: 0 4px 15px rgba(255, 51, 102, 0.2);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #ff1a4d 0%, #ff5252 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 51, 102, 0.3);
    color: white;
}

.table-responsive {
    margin-top: 20px;
}

.table {
    color: #a8b2d1;
    margin-bottom: 0;
}

.table thead th {
    background: rgba(76, 110, 245, 0.1);
    border-bottom: 2px solid rgba(76, 110, 245, 0.2);
    color: #fff !important;
    font-weight: 600;
    padding: 15px;
    text-transform: uppercase;
    font-size: 0.9rem;
}

.table tbody td {
    border-top: 1px solid rgba(255, 255, 255, 0.05);
    padding: 15px;
    vertical-align: middle;
}

.table-striped tbody tr:nth-of-type(odd) {
    background: rgba(255, 255, 255, 0.02);
}
</style>

<div class="col-md-10 mx-auto ctmain-table">

    <div class="card ctcard">
        <div class="card-header card-header-warning">
            <center><h2><i class="icon icon-settings"></i> Paramètres</h2></center>
        </div>
        <div class="card-body">
            <form method="post">

                <h4>Boutons</h4>
                <div class="d-flex flex-wrap" style="gap: 24px; margin-bottom: 30px;">
                    <?php foreach ($settings['buttons'] as $key => $btn): ?>
                        <div style="
                            background: rgba(255, 255, 255, 0.03);
                            border: 1px solid rgba(255, 255, 255, 0.05);
                            border-radius: 12px;
                            padding: 20px;
                            min-width: 280px;
                            max-width: 100%;
                            flex: 1 1 300px;
                            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                        ">
                            <label class="form-label" for="btn_<?= $key ?>">URL <?= ucfirst($key) ?></label>
                            <input type="text" id="btn_<?= $key ?>" name="btn_url[<?= $key ?>]" class="form-control" value="<?= htmlspecialchars($btn['url']) ?>">
                            <div class="mt-3">
                                <label style="color: #ccc;">
                                    <input type="checkbox" name="btn_enabled[<?= $key ?>]" <?= $btn['enabled'] ? 'checked' : '' ?>>
                                    Activer <?= ucfirst($key) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr>

                <h4>Actualités</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date (AAAA-MM-JJ)</th>
                                <th>Texte</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings['news'] as $i => $news): ?>
                                <tr>
                                    <td>
                                        <input type="date" name="news_date[]" value="<?= htmlspecialchars($news['date']) ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="news_text[]" value="<?= htmlspecialchars($news['text']) ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <a href="?delete_news=<?= $i ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette actualité ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>
                                    <input type="date" name="new_news_date" class="form-control" placeholder="Nouvelle date">
                                </td>
                                <td>
                                    <input type="text" name="new_news_text" class="form-control" placeholder="Nouvelle actualité">
                                </td>
                                <td>
                                    <button type="submit" name="submit" class="btn btn-info btn-sm">Ajouter</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <h4>Téléchargements</h4>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Chemin</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($settings['downloads'] as $i => $download): ?>
                                <tr>
                                    <td>
                                        <input type="text" name="download_name[]" value="<?= htmlspecialchars($download['name'] ?? '') ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="download_path[]" value="<?= htmlspecialchars($download['path'] ?? '') ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="download_note[]" value="<?= htmlspecialchars($download['note'] ?? '') ?>" class="form-control" required>
                                    </td>
                                    <td>
                                        <a href="?delete_download=<?= $i ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer ce téléchargement ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>
                                    <input type="text" name="new_download_name" class="form-control" placeholder="Nouveau nom">
                                </td>
                                <td>
                                    <input type="text" name="new_download_path" class="form-control" placeholder="Nouveau chemin">
                                </td>
                                <td>
                                    <input type="text" name="new_download_note" class="form-control" placeholder="Nouvelle note">
                                </td>
                                <td>
                                    <button type="submit" name="submit" class="btn btn-info btn-sm">Ajouter</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <hr>

                <button type="submit" name="submit" class="btn btn-info btn-lg">Sauvegarder les paramètres</button>

            </form>
        </div>
    </div>

</div>

<?php include('includes/footer.php'); ?>
