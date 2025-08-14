<?php 
include ('includes/header.php');
$table_name = 'welcome';
$data = ['message_one' => 'Welcome','message_two' => 'Please Uservice and enter username\/password to login','message_three' =>'Enjoy!'];
$db->insertIfEmpty($table_name, $data);
$res = $db->select($table_name, '*', '', '');

if(isset($_POST['submit'])){
	unset($_POST['submit']);
	$updateData = $_POST;
	$db->update($table_name, $updateData, 'id = :id',[':id' => 1]);
	echo "<script>window.location.href='". $table_name.".php?status=1'</script>";
}

?>

        <div class="col-md-8 mx-auto ctmain-table">
            <div class="card-body">
                <div class="card ctcard">
                    <div class="card-header card-header-warning">
                        <center>
                            <h2><i class="icon icon-bullhorn"></i> Login Messages</h2>
                        </center>
                    </div>
                    
                    <div class="card-body">
                            <form method="post">
                                <div class="form-group ctinput">
                                    <label class="form-label " >Messaage 1</label>
                                        <input class="form-control"  name="message_one" value="<?=$res[0]['message_one'] ?>" type="text"/>
                                </div>
                                <div class="form-group ctinput">
                                    <label class="form-label " >Messaage 2</label>
                                        <input class="form-control"  name="message_two" value="<?=$res[0]['message_two'] ?>" type="text"/>
                                </div>
                                <div class="form-group ctinput">
                                    <label class="form-label " >Messaage 3</label>
                                        <input class="form-control"  name="message_three" value="<?=$res[0]['message_three'] ?>" type="text"/>
                                </div>
                                <div class="form-group ctinput">
                                    <center>
                                        <button class="btn btn-info " name="submit" type="submit">
                                            <i class="icon icon-check"></i> Submit
                                        </button>
                                    </center>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>

<?php include ('includes/footer.php');?>