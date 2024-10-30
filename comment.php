<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == null || $security_key == null) {
    header('Location: index.php');
}

// check admin
$user_role = $_SESSION['user_role'];

$task_t = $_GET['t_title'];
$com_userid = $_GET['com_userid'];

if (isset($_POST['new_comment'])) {
    $obj_admin->add_new_comment($_POST, $task_t, $com_userid);
}

$page_name = "Edit Task";
include("include/sidebar.php");

$sql = "SELECT * FROM task_info WHERE task_id='$task_t' ";
$info = $obj_admin->manage_all_info($sql);
$row = $info->fetch(PDO::FETCH_ASSOC);

?>



<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="well">
                        <h3 class="text-center bg-primary" style="padding: 7px;">Komentar </h3><br>

                        <div class="row">
                            <div class="col-md-12">
                                <form class="form-horizontal" role="form" action="" method="post" autocomplete="off">
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Naslov</label>
                                        <div class="col-sm-7">
                                            <input type="text" placeholder="Unesite Naslov" id="com_title" name="com_title"
                                                   list="expense" class="form-control" value="" val required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Komentar </label>
                                        <div class="col-sm-7">
                                            <textarea name="com_description" id="com_description"
                                                      placeholder="Napisite vas Komentar" class="form-control" rows="5"
                                                      cols="5"></textarea>
                                        </div>
                                    </div>


                                    <div class="form-group">
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-3 col-sm-3">

                                        </div>

                                        <div class="col-sm-3">
                                            <button type="submit" name="new_comment" class="btn btn-success-custom">
                                                Komentarisi
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    flatpickr('#t_end_time', {
        enableTime: true
    });

</script>


<?php

include("include/footer.php");

?>

