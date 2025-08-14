<?php 
include ('includes/header.php');
//table name
$table_name = "dns";
$page = "main.php";

//table call
$res = $db->select($table_name, '*', '', '');

//update call
@$resU = $db->select($table_name, '*', 'id = :id', '', [':id' => $_GET['update']]);

if(isset($_POST['submitU'])){
    unset($_POST['submitU']);
    $updateData = $_POST;
    $db->update($table_name, $updateData, 'id = :id',[':id' => $_GET['update']]);
    echo "<script>window.location.href='".$page."?status=1'</script>";
}

//submit new
if (isset($_POST['submit'])){
    unset($_POST['submit']);
    $db->insert($table_name, $_POST);
    $db->close();
    echo "<script>window.location.href='".$page."?status=1'</script>";
}

//delete row
if(isset($_GET['delete'])){
    $db->delete($table_name, 'id = :id',[':id' => $_GET['delete']]);
    echo "<script>window.location.href='".$page."?status=2'</script>";
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

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirmer</h2>
            </div>
            <div class="modal-body">
                Voulez-vous vraiment le supprimer ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Annuler</button>
                <a class="btn btn-danger btn-ok">Supprimer</a>
            </div>
        </div>
    </div>
</div>

<?php
if (isset($_GET['create'])){
?>
<!-- Formulario de creación -->
<div class="col-md-10 mx-auto ctmain-table">
    <div class="card-body">
        <div class="card ctcard">
            <div class="card-header card-header-warning">
                <center>
                    <h2><i class="icon icon-bullhorn"></i> Nom et DNS du portail</h2>
                </center>
            </div>
            
            <div class="card-body">
                <div class="col-12">
                    <h3>Ajouter le nom et DNS du portail</h3>
                </div>
                <form method="post">
                    <div class="form-group ctinput">
                        <label class="form-label" for="title">Nom</label>
                        <input class="form-control" id="description" name="title" placeholder="Nom" type="text"/>
                    </div>
                    <div class="form-group ctinput">
                        <label class="form-label" for="dns">DNS</label>
                        <input class="form-control" id="description" name="url" placeholder="DNS" type="text"/>
                    </div>
                    <div class="form-group ctinput">
                        <center>
                            <button class="btn btn-info" name="submit" type="submit">
                                <i class="icon icon-check"></i> Ajouter
                            </button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
} else if (isset($_GET['update'])){ 
?>
<!-- Formulario de actualización -->
<div class="col-md-10 mx-auto ctmain-table">
    <div class="card-body">
        <div class="card ctcard">
            <div class="card-header card-header-warning">
                <center>
                    <h2><i class="icon icon-bullhorn"></i> Modification du Portail</h2>
                </center>
            </div>
            
            <div class="card-body">
                <div class="col-12">
                    <h3>Modification du nom et DNS du portail</h3>
                </div>
                <form method="post">
                    <input type="hidden" name="id" value="<?=$_GET['update'] ?>">
                    <div class="form-group ctinput">
                        <label class="form-label" for="title">Nom</label>
                        <input class="form-control" id="description" name="title" placeholder="Nom" value="<?=$resU[0]['title'] ?>" type="text"/>
                    </div>
                    <div class="form-group ctinput">
                        <label class="form-label" for="dns">DNS</label>
                        <input class="form-control" id="description" name="url" placeholder="DNS" value="<?=$resU[0]['url'] ?>" type="text"/>
                    </div>
                    <div class="form-group ctinput">
                        <center>
                            <button class="btn btn-info" name="submitU" type="submit">
                                <i class="icon icon-check"></i> Confirmer
                            </button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
} else {
?>
<!-- Tabla principal -->
<div class="col-md-10 mx-auto ctmain-table">
    <div class="card-body">
        <div class="card ctcard">
            <div class="card-header card-header-warning">
                <center>
                    <h2><i class="icon icon-commenting"></i> Ajoutez ou supprimez un DNS</h2>
                </center>
            </div>
            <div class="card-body">
                <div class="col-12">
                    <center>
                        <a id="button" href="./<?=$page ?>?create" class="btn btn-info">
                            <i class="fa fa-plus"></i> Ajouter
                        </a>
                    </center>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>DNS</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($res as $row) { ?>
                            <tr>
                                <td><?=$row['title'] ?></td>
                                <td><?=$row['url'] ?></td>
                                <td>
                                    <a class="btn btn-info btn-ok" href="<?=$page ?>?update=<?=$row['id'] ?>">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <a class="btn btn-danger btn-ok" href="#" data-href="<?=$page ?>?delete=<?=$row['id'] ?>" data-toggle="modal" data-target="#confirm-delete">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>

<?php include ('includes/footer.php'); ?>
</body>
</html>