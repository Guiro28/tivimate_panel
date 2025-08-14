<?php include('includes/header.php'); ?>

<?php
// Chargement du fichier settings.json
$settingsFile = __DIR__ . '/settings.json';
$defaultSettings = [
    'buttons' => [
        'discord' => ['label' => 'Discord', 'url' => 'https://url/xxxxxxxxxx', 'enabled' => true],
        'telegram' => ['label' => 'Telegram', 'url' => 'https://t.me/xxxxxxxxx', 'enabled' => true],
        'contact' => ['label' => 'Contact', 'url' => '', 'enabled' => true],
        'forum' => ['label' => 'Forum', 'url' => '', 'enabled' => true],
    ],
    'news' => [
        ['date' => '15 juillet 2025', 'text' => 'Ajout de MT Manager dans les téléchargement'],
        ['date' => '15 juillet 2025', 'text' => 'Ajout d\'une page d\'accueil'],
        ['date' => '14 juillet 2025', 'text' => 'Ajout des backups et APKs Tivimate'],
    ]
];

if (file_exists($settingsFile)) {
    $settings = json_decode(file_get_contents($settingsFile), true);
    if (!$settings) $settings = $defaultSettings;
} else {
    $settings = $defaultSettings;
}
?>

<style>
.home-container {
    margin-top: 40px;
    padding: 0 15px;
}

.home-card {
    background: linear-gradient(45deg, #1a1a2e 0%, #16213e 100%);
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    padding: 40px;
    text-align: center;
    color: #a8b2d1;
}

.home-card h1 {
    color: #fff;
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.home-card p {
    font-size: 1.2rem;
    margin-bottom: 30px;
}

.home-card a.btn {
    margin: 10px;
}

.news-section {
    margin-top: 40px;
    padding: 25px;
    background: linear-gradient(45deg, #0f0f1a 0%, #111827 100%);
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    color: #a8b2d1;
}

.news-section h3 {
    color: #fff;
    margin-bottom: 20px;
}

.news-item {
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

/* Estilos para botones */
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
}

.btn-info:hover {
    background: linear-gradient(45deg, #3b5ef0 0%, #4f6fff 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(76, 110, 245, 0.3);
}

.btn-danger {
    background: linear-gradient(45deg, #FF3366 0%, #FF6B6B 100%);
    box-shadow: 0 4px 15px rgba(255, 51, 102, 0.2);
}

.btn-danger:hover {
    background: linear-gradient(45deg, #ff1a4d 0%, #ff5252 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 51, 102, 0.3);
}

.btn-discord {
    background: linear-gradient(45deg, #5865F2, #7289DA);
    color: white;
    box-shadow: 0 4px 15px rgba(88, 101, 242, 0.3);
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    display: inline-block;
}

.btn-discord:hover {
    background: linear-gradient(45deg, #4e5bd4, #677bc4);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(88, 101, 242, 0.4);
    text-decoration: none;
    color: white;
}

.btn-telegram {
    background: linear-gradient(45deg, #0088cc, #229ED9);
    color: white;
    box-shadow: 0 4px 15px rgba(0, 136, 204, 0.3);
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    display: inline-block;
}

.btn-telegram:hover {
    background: linear-gradient(45deg, #007ab8, #1c90c7);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 136, 204, 0.4);
    text-decoration: none;
    color: white;
}

.btn-contact {
    background: linear-gradient(45deg, #FF3366, #FF6B6B);
    color: white;
    box-shadow: 0 4px 15px rgba(255, 51, 102, 0.2);
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    display: inline-block;
}

.btn-contact:hover {
    background: linear-gradient(45deg, #ff1a4d, #ff5252);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 51, 102, 0.3);
    text-decoration: none;
    color: white;
}

.btn-forum {
    background: linear-gradient(45deg, #8e44ad, #9b59b6);
    color: white;
    box-shadow: 0 4px 15px rgba(155, 89, 182, 0.3);
    transition: all 0.3s ease;
    padding: 10px 20px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
    display: inline-block;
}

.btn-forum:hover {
    background: linear-gradient(45deg, #7d3c98, #884ea0);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(155, 89, 182, 0.4);
    text-decoration: none;
    color: white;
}
</style>

<div class="col-md-10 mx-auto home-container">
    <div class="home-card">

        <!-- LOGO -->
        <img src="./img/login_logo.png" alt="Logo" style="max-width: 240px; margin-bottom: 40px;">

        <h1>Bienvenue sur votre panel Tivimate</h1>
        <p>Gérez facilement les DNS de votre portail IPTV. </p>

        <?php
        // Affichage dynamique des boutons si activés
        $btnClasses = [
            'discord' => 'btn-discord',
            'telegram' => 'btn-telegram',
            'contact' => 'btn-contact',
            'forum' => 'btn-forum',
        ];

foreach ($settings['buttons'] as $key => $btn) {
    if (!empty($btn['enabled'])) {
        $url = !empty($btn['url']) ? $btn['url'] : '#';
        $label = !empty($btn['label']) ? htmlspecialchars($btn['label']) : ucfirst($key);
        $class = $btnClasses[$key] ?? 'btn-info';
        $icon = '';
        switch ($key) {
            case 'discord': $icon = '<i class="fa fa-discord"></i> '; break;
            case 'telegram': $icon = '<i class="fa fa-telegram"></i> '; break;
            case 'contact': $icon = '<i class="fa fa-envelope"></i> '; break;
            case 'forum': $icon = '<i class="fa fa-comments"></i> '; break;
        }
        echo "<a href=\"".htmlspecialchars($url)."\" target=\"_blank\" class=\"btn $class\">$icon$label</a>";
    }
}
        ?>
    </div>

    <!-- SECTION ACTUALITÉS -->
    <div class="news-section mt-5">
        <h3><i class="fa fa-newspaper"></i> Actualités</h3>

        <?php if (!empty($settings['news']) && is_array($settings['news'])): ?>
            <?php foreach ($settings['news'] as $news): ?>
                <div class="news-item">
                    <strong>[<?= htmlspecialchars($news['date']) ?>]</strong> – <?= htmlspecialchars($news['text']) ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune actualité disponible.</p>
        <?php endif; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>
