<?php
include('includes/header.php');

$table_name = 'user';
$page = 'user.php';

// Suppression utilisateur (si paramètre 'delete' est passé en GET et différent de 1)
if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    if ($deleteId !== 1) {
        $db->delete($table_name, 'id = :id', [':id' => $deleteId]);
        echo "<script>window.location.href='".$page."?status=deleted'</script>";
        exit;
    } else {
        echo "<script>alert('L\'utilisateur admin ne peut pas être supprimé.');</script>";
    }
}

// Récupère tous les utilisateurs
$users = $db->select($table_name, '*', '', 'id ASC');

// Récupère l'utilisateur à éditer (si paramètre présent dans l'URL)
$editUser = null;
if (isset($_GET['edit'])) {
    $editUser = $db->select($table_name, '*', 'id = :id', '', [':id' => $_GET['edit']]);
    $editUser = $editUser[0] ?? null;
}

// Gère le formulaire (ajout ou modification)
if (isset($_POST['submit'])) {
    unset($_POST['submit']);
    $formData = $_POST;

    // Hashage du mot de passe avant insertion ou mise à jour
    if (!empty($formData['password'])) {
        $formData['password'] = password_hash($formData['password'], PASSWORD_DEFAULT);
    }

    if (!empty($formData['id'])) {
        // Modification
        $id = $formData['id'];
        unset($formData['id']);
        $db->update($table_name, $formData, 'id = :id', [':id' => $id]);
    } else {
        // Ajout
        $db->insert($table_name, $formData);
    }

    echo "<script>window.location.href='".$page."?status=1'</script>";
}
?>

<style>
/* Estilos generales */
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
            <center><h2><i class="icon icon-user"></i> <?= $editUser ? "Modifier" : "Ajouter" ?> un utilisateur</h2></center>
        </div>
        <div class="card-body">
            <form method="post">
                <?php if ($editUser): ?>
                    <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
                <?php endif; ?>

                <div class="form-group">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" class="form-control" name="username" required value="<?= htmlspecialchars($editUser['username'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" <?= $editUser ? '' : 'required' ?> placeholder="<?= $editUser ? 'Laisser vide pour garder le mot de passe actuel' : '' ?>">
                </div>

                <center>
                    <button type="submit" name="submit" class="btn btn-info">
                        <i class="icon icon-check"></i> <?= $editUser ? "Modifier" : "Ajouter" ?>
                    </button>
                </center>
            </form>
        </div>
    </div>

    <div class="card ctcard mt-4">
        <div class="card-header card-header-warning">
            <center><h2><i class="icon icon-list"></i> Liste des utilisateurs</h2></center>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom d'utilisateur</th>
                        <th>Mot de passe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td>**********</td>
                        <td>
                            <a href="?edit=<?= $user['id'] ?>" class="btn btn-info btn-sm">Modifier</a>
                            <?php if ($user['id'] != 1): ?>
                                <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');" class="btn btn-danger btn-sm">Supprimer</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
</body>
</html>