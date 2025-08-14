<?php include('includes/header.php'); ?>

<?php
$jsonFile = __DIR__ . '/settings.json';
if (file_exists($jsonFile)) {
    $settings = json_decode(file_get_contents($jsonFile), true);
    $fichiers = $settings['downloads'] ?? [];
} else {
    $fichiers = [];
}
?>

<style>
/* Estilos generales */
.ctmain-table {
    margin-top: 20px;
    padding: 0 15px;

}

/* Le reste des styles reste inchangé */

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

/* Estilos para formularios */
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

/* Estilos para la tabla */
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

/* Modal de confirmación */
.modal-content {
    background: linear-gradient(45deg, #1a1a2e 0%, #16213e 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
}

.modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 20px;
}

.modal-header h2 {
    font-size: 1.5rem;
    margin: 0;
}

.modal-body {
    padding: 20px;
    font-size: 1.1rem;
}

.modal-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: 20px;
}

/* Espaciado y alineación */
.col-12 {
    margin-bottom: 20px;
}

.col-12 h3 {
    color: #fff;
    margin-bottom: 20px;
    font-size: 1.3rem;
}
</style>

<div class="col-md-10 mx-auto ctmain-table">
    <div class="card ctcard">
        <div class="card-header card-header-warning">
            <center>
                <h2><i class="icon icon-download"></i> Téléchargement</h2>
            </center>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Note</th>
                            <th>Téléchargement</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fichiers as $fichier): ?>
                            <tr>
                                <td><?= htmlspecialchars($fichier['name']) ?></td>
                                <td><?= htmlspecialchars($fichier['note']) ?></td>
                                <td>
                                    <a class="btn btn-info" href="<?= htmlspecialchars($fichier['path']) ?>" download target="_blank">
                                        <i class="fa fa-download"></i> Télécharger
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($fichiers)): ?>
                            <tr><td colspan="3">Aucun fichier disponible pour le moment.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
