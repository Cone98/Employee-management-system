<?php

require 'authentication.php'; // admin authentication check

// auth check
if (isset($_SESSION['admin_id'])) {
    $user_id = $_SESSION['admin_id'];
    $user_name = $_SESSION['admin_name'];
    $security_key = $_SESSION['security_key'];
    if ($user_id != null && $security_key != null) {
        header('Location: task-info.php');
    }
}

if (isset($_POST['login_btn'])) {
    $info = $obj_admin->admin_login_check($_POST);
}


$page_name = "Login";
include("include/login_header.php");

?>

<div class="row">
    <div class="col-md-4 col-md-offset-3">
        <div class="well" style="position:relative;top:20vh;">
            <center>
                <h2 style="margin-top:1px;">Sistem za upravljanje zadacima zaposlenih</h2>
            </center>
            <form class="form-horizontal form-custom-login" action="" method="post">
                <div class="form-heading">
                    <h2 class="text-center">Login Panel</h2>
                </div>
                <?php
                if (isset($info)) { ?>
                    <h5 class="alert alert-danger"><?php
                    echo $info; ?></h5><?php
                } ?>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" name="username">
                </div>
                <div class="form-group"
                     ng-class="{'has-error': loginForm.password.$invalid &amp;&amp; loginForm.password.$dirty, 'has-success': loginForm.password.$valid}">
                    <input type="password" class="form-control" placeholder="Lozinka" name="admin_password">
                </div>
                <button type="submit" name="login_btn" class="btn btn-info pull-right">Login</button>
            </form>
            <div class="btn-group">
                <button class="btn btn-success btn-menu" data-toggle="modal" data-target="#myModal">Registracija
                </button>
            </div>
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h2 class="modal-title text-center">Registracija</h2>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php
                                    if (isset($error)) { ?>
                                        <h5 class="alert alert-danger"><?php
                                        echo $error; ?></h5><?php
                                    } ?>
                                    <form role="form" action="" method="post" autocomplete="off">
                                        <div class="form-horizontal">
                                            <div class="form-heading">
                                                <h2 class="text-center"></h2>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">Ime i prezime</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Unesite ime i prezime"
                                                           name="em_fullname" list="expense"
                                                           class="form-control input-custom" id="default" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">Username</label>
                                                <div class="col-sm-6">
                                                    <input type="text" placeholder="Unesite username"
                                                           name="em_username" class="form-control input-custom"
                                                           required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">Email</label>
                                                <div class="col-sm-6">
                                                    <input type="email" placeholder="Unesite Email"
                                                           name="em_email" class="form-control input-custom"
                                                           required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">Lozinka</label>
                                                <div class="col-sm-6">
                                                    <input type="password" placeholder="Unesite lozinku"
                                                           name="em_pass" class="form-control input-custom" required="">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-sm-4">Ponovi lozinku</label>
                                                <div class="col-sm-6">
                                                    <input type="password" placeholder="Ponovi unesite lozinku"
                                                           name="rem_pass" class="form-control input-custom"
                                                           required="">
                                                </div>
                                            </div>
                                            <div class="form-group"></div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-3 col-sm-3">
                                                    <button type="submit" name="register_btn"
                                                            class="btn btn-success-custom">Register
                                                    </button>

                                                </div>
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
</div>


<?php

include("include/footer.php");


if (isset($_POST['register_btn'])) {
    $con = mysqli_connect("Localhost", "root", "", "projekat");
    if (mysqli_connect_errno()) {
        echo "Connection Fail" . mysqli_connect_error();
    }

    $FName = $_POST['em_fullname'];
    $LName = $_POST['em_username'];
    $empcode = 2;
    $Email = $_POST['em_email'];
    $Password = $_POST['em_pass'];
    $RPassword = $_POST['rem_pass'];
    $ret = mysqli_query($con, "select email from tbl_admin where email='$Email'");
    $result = mysqli_fetch_array($ret);

    if ($result > 0) {
        $msg = "This email already associated with another account";
    } elseif ($Password != $RPassword) {
        $msg = "Lozinke se ne poklapaju";
    } else {
        $query = mysqli_query(
            $con,
            "insert into tbl_admin(fullname, username, email, password, user_role) value('$FName', '$LName', '$Email', '$Password', '$empcode')"
        );

        if ($query) {
            $msg = "You have successfully registered";
        } else {
            $msg = "Something Went Wrong. Please try again";
        }
    }
    echo '<script language="javascript">';
    echo 'alert("' . $msg . '")';
    echo '</script>';
}

?>
