<?php


require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == null || $security_key == null) {
    header('Location: index.php');
}


$user_role = $_SESSION['user_role'];


if (isset($_POST['add_task_post'])) {
    $arrayEmp = $_POST['assign_to'];
    $obj_admin->add_new_task($_POST);
}

if (isset($_POST['remove_taskgroup_post'])) {
    $groupid = $_POST['remove_group'];
    $con = mysqli_connect("Localhost", "root", "", "projekat");
    if (mysqli_connect_errno()) {
        echo "Connection Fail" . mysqli_connect_error();
    }
    $ret = mysqli_query($con, "DELETE FROM taskgroup WHERE ID = '$groupid'");
    $ret = mysqli_query($con, "DELETE FROM task_info WHERE taskgroup = '$groupid'");
}


if (isset($_POST['prioritet_post'])) {
    $priorit = $_POST['prior_num'];
    header("Location: task-info1.php ?prioritet=$priorit");
}

if (isset($_POST['AllTasks'])) {
    header("Location: task-info.php");
}

if (isset($_POST['titletask_post'])) {
    $titletask = $_POST['titletask'];
    header("Location: task-info1.php ?titletask=$titletask");
}
if (isset($_POST['task_odDo'])) {
    $time1 = $_POST['t_time_od'];
    $time2 = $_POST['t_time_do'];
    header("Location: task-info1.php?time_od=$time1&time_do=$time2 ");
}


if (isset($_POST['asigneessPost'])) {
    $Employees = $_POST['assignees_to'];
    header("Location: task-info1.php?Employees=$Employees");
}

if (isset($_POST['add_taskgroup_post'])) {
    $obj_admin->add_new_task_group($_POST);
}


$page_name = "Task_Info";
include("include/sidebar.php");


if (isset($_GET['prioritet'])) {
    $prioritet = $_GET['prioritet'];

    if ($user_role == 1 || $user_role == 3) {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id)
				  WHERE a.prioritet=$prioritet
						GROUP BY a.t_title
                        ORDER BY a.task_id DESC";
    } else {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id) 
                  WHERE a.t_user_id = $user_id and a.prioritet=$prioritet
				  GROUP BY a.t_title
                  ORDER BY a.task_id DESC";
    }

    $info = $obj_admin->manage_all_info($sql);
    $serial = 1;
    $num_row = $info->rowCount();
}

if (isset($_GET['titletask'])) {
    $titletask = $_GET['titletask'];
    if ($user_role == 1 || $user_role == 3) {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id)
				  WHERE a.t_title = '$titletask'
						GROUP BY a.t_title
                        ORDER BY prioritet";
    } else {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id) 
                  WHERE a.t_user_id = $user_id and a.t_title = '$titletask'
				  GROUP BY a.t_title
                  ORDER BY prioritet";
    }

    $info = $obj_admin->manage_all_info($sql);
    $serial = 1;
    $num_row = $info->rowCount();
}

if (isset($_GET['time_od'], $_GET['time_do'])) {
    $time1 = $_GET['time_od'];
    $time2 = $_GET['time_do'];


    if ($user_role == 1 || $user_role == 3) {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id)
				  WHERE a.t_end_time between '$time1' and '$time2'
						GROUP BY a.t_title
                        ORDER BY a.t_end_time";
    } else {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id) 
                  WHERE a.t_end_time between '$time1' and '$time2' and a.t_user_id = $user_id 
				  GROUP BY a.t_title
                  ORDER BY a.t_end_time";
    }

    $info = $obj_admin->manage_all_info($sql);
    $serial = 1;
    $num_row = $info->rowCount();
}


if (isset($_GET['Employees'])) {
    $asignees = $_GET['Employees'];
    if ($user_role == 1 || $user_role == 3) {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id)
				  WHERE b.user_id = $asignees
						GROUP BY a.t_title
                        ORDER BY prioritet DESC";
    } else {
        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id) 
                  WHERE a.t_user_id = $user_id and b.user_id = $asignees
				  GROUP BY a.t_title
                  ORDER BY prioritet";
    }

    $info = $obj_admin->manage_all_info($sql);
    $serial = 1;
    $num_row = $info->rowCount();
}


if (isset($_GET['delete_task'])) {
    $action_id = $_GET['t_title'];
    $con = mysqli_connect("Localhost", "root", "", "projekat");
    if (mysqli_connect_errno()) {
        echo "Connection Fail" . mysqli_connect_error();
    }
    $ret = mysqli_query($con, "DELETE FROM task_info WHERE t_title = '$action_id'");
    //$result=mysqli_fetch_array($ret);


    header("Location: task-info.php");
}


?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Kreiraj novi zadatak</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Naslov zadatka</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Naslov zadatka" id="task_title" name="task_title"
                                               list="expense" class="form-control" id="default" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Opis zadatka</label>
                                    <div class="col-sm-7">
                                        <textarea name="task_description" id="task_description"
                                                  placeholder="Opis zadatka" class="form-control" rows="5"
                                                  cols="5"></textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-5">Rok za izvrsenje</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_end_time" id="t_end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Zaposleni</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sqlc = "SELECT user_id, fullname FROM tbl_admin WHERE user_role in (2, 3)";
                                        $infoc = $obj_admin->manage_all_info($sqlc);
                                        ?>
                                        <select name="assign_to[]" class="form-control multiple-select" multiple>


                                            <?php
                                            while ($rowc = $infoc->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $rowc['user_id']; ?>"><?php
                                                    echo isset($assign_to) && in_array(
                                                        $rowc['user_id'],
                                                        explode(',', $assign_to)
                                                    ) ? "selected" : '' ?><?php
                                                    echo $rowc['fullname']; ?></option>
                                                <?php
                                            } ?>

                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Rukovodilac</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sqlc = "SELECT user_id, fullname FROM tbl_admin WHERE user_role in (3)";
                                        $infoc = $obj_admin->manage_all_info($sqlc);
                                        ?>
                                        <select class="form-control" name="assign_ruk" id="aassign_ruk" required>
                                            <option value="">Izaberi rukovodioca...</option>

                                            <?php
                                            while ($rowc = $infoc->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $rowc['user_id']; ?>"><?php
                                                    echo $rowc['fullname']; ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Group</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sqlc = "SELECT ID, name FROM taskgroup";
                                        $infoc = $obj_admin->manage_all_info($sqlc);
                                        ?>
                                        <select class="form-control" name="assign_group" id="aassign_group" required>
                                            <option value="">Izaberi grupu zadataka...</option>

                                            <?php
                                            while ($rowc = $infoc->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $rowc['ID']; ?>"><?php
                                                    echo $rowc['name']; ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Prioritet</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="prioritetTask" id="userrRole" required>
                                                <option value="">Izaberi prioritet...</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                            </select>
                                        </div>

                                    </div>


                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="add_task_post" class="btn btn-success-custom">Kreiraj zadatak
                                            
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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

<div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Kreiraj novu grupu zadataka</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Naziv grupe</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Naziv grupe" id="taskgroup_title"
                                               name="taskgroup_title" list="expense" class="form-control" id="default"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="add_taskgroup_post" class="btn btn-success-custom">
                                            Kreiraj grupu
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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


<div class="modal fade" id="myModal12" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Pretraga po prioritetu</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Prioritet</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Prioritet (1-10)" id="prioritet"
                                               name="prior_num" list="expense" class="form-control" id="default"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="prioritet_post" class="btn btn-success-custom">
                                            Pretrazi
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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
<div class="modal fade" id="myModal13" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Pretraga po naslovu</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Naslov</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Naslov" id="prioritet" name="titletask"
                                               list="expense" class="form-control" id="default" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="titletask_post" class="btn btn-success-custom">
                                            Pretrazi
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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

<div class="modal fade" id="myModal15" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Pretraga po zaposlenom</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">

                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label class="control-label col-sm-5">Zaposleni</label>
                                            <div class="col-sm-7">
                                                <?php
                                                $sqlc = "SELECT user_id, fullname FROM tbl_admin WHERE user_role in (2, 3)";
                                                $infoc = $obj_admin->manage_all_info($sqlc);
                                                ?>
                                                <select name="assignees_to" class="form-control multiple-select">


                                                    <?php
                                                    while ($rowc = $infoc->fetch(PDO::FETCH_ASSOC)) { ?>
                                                        <option value="<?php
                                                        echo $rowc['user_id']; ?>"><?php
                                                            echo $rowc['fullname']; ?></option>
                                                        <?php
                                                    } ?>

                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="asigneessPost" class="btn btn-success-custom">
                                            Pretrazi
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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

<div class="modal fade" id="myModal21" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Obrisi grupu zadataka</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Naziv grupe</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sqlh = "SELECT ID, name FROM taskgroup";
                                        $infoh = $obj_admin->manage_all_info($sqlh);
                                        ?>
                                        <select class="form-control" name="remove_group" id="removee_group" required>
                                            <option value="">Izaberi grupu...</option>

                                            <?php
                                            while ($rowh = $infoh->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $rowh['ID']; ?>"><?php
                                                    echo $rowh['name']; ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="remove_taskgroup_post"
                                                class="btn btn-success-custom">Obrisi grupu
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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

<div class="modal fade" id="myModal14" role="dialog">
    <div class="modal-dialog add-category-modal">


        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title text-center">Pretraga po datumu</h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form role="form" action="" method="post" autocomplete="off">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Datum od</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_time_od" id="t_end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Datum do</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_time_do" id="t_end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="task_odDo" class="btn btn-success-custom">Pretrazi
                                        </button>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-danger-custom" data-dismiss="modal">
                                            Cancel
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


<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="gap"></div>
            <div class="row">
                <div class="col-md-8">
                    <div class="btn-group">
                        <?php
                        if ($user_role == 1 || $user_role == 3) { ?>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal1"
                                        button style='margin-right:10px'>Kreiraj novu grupu zadataka
                                </button>
                                <br>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal"
                                        button style='margin-right:16px'>Kreiraj zadatak
                                </button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal21"
                                        button style='margin-right:16px'>Obrisi grupu zadataka
                                </button>
                            </div>
                            <?php
                        } ?>
                        <hr>
                        <div class="btn-group">
                            <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal12" button
                                    style='margin-right:16px'>Pretraga po prioritetu
                            </button>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal14" button
                                    style='margin-right:16px'>Pretraga po roku izvrsenja
                            </button>
                        </div>
                        <?php
                        if ($user_role == 1 || $user_role == 3) { ?>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal13"
                                        button style='margin-right:16px'>Pretraga po Naslovu
                                </button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-warning btn-menu" data-toggle="modal" data-target="#myModal15"
                                        button style='margin-right:16px'>Pretraga po zaposlenom
                                </button>
                            </div>
                            <?php
                        } ?>
                        <hr>
                        <div class="btn-group">
                            <form action="" method="post">
                                <button type="submit" name="AllTasks" class="btn btn-warning btn-menu"
                                        data-toggle="modal" button style='margin-right:16px'>Prikazi sve zadatke
                                </button>
                            </form>
                        </div>
                        </hr>

                        </hr>


                    </div>

                </div>


            </div>
            <center><h3>Task Management Section</h3></center>
            <div class="gap"></div>

            <div class="gap"></div>

            <div class="table-responsive">
                <table class="table table-codensed table-custom">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Naziv zadatka</th>
                        <th>Clanovi</th>
                        <th>Grupa</th>
                        <th>Rukovodilac</th>
                        <th>Rok za izvrsenje</th>
                        <th>Prioritet</th>
                        <th>Status</th>
                        <th>Akcija</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if ($num_row == 0) {
                        echo '<tr><td colspan="7">No Data found</td></tr>';
                    }

                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                        <tr>
                            <td><?php
                                echo $serial;
                                $serial++; ?></td>
                            <td><?php
                                echo $row['t_title'];
                                $titl = $row['t_title'] ?></td>
                            <?php
                            $sql12 = "SELECT b.fullname FROM task_info JOIN tbl_admin b ON(t_user_id = b.user_id) WHERE t_title = '$titl' ORDER BY task_id DESC;"
                            ?>
                            <?php
                            $info12 = $obj_admin->manage_all_info($sql12);
                            $num_row1 = $info12->rowCount();

                            ?>
                            <td>
                                <?php
                                while ($row1 = $info12->fetch(PDO::FETCH_ASSOC)) { ?>

                                    <?php
                                    $imp = implode(", ", $row1);
                                    echo $imp;
                                    echo ", "; ?>
                                    <?php
                                } ?>
                            </td>

                            <td><?php
                                echo $row['name']; ?></td>
                            <td><?php
                                echo $row['sef']; ?></td>
                            <td><?php
                                echo $row['t_end_time']; ?></td>
                            <td><?php
                                echo $row['prioritet']; ?></td>
                            <td>
                                <?php
                                if ($row['status'] == 1) {
                                    echo "In Progress <span style='color:#d4ab3a;' class=' glyphicon glyphicon-refresh' >";
                                } elseif ($row['status'] == 2) {
                                    echo "Completed <span style='color:#00af16;' class=' glyphicon glyphicon-ok' >";
                                } else {
                                    echo "Incomplete <span style='color:#d00909;' class=' glyphicon glyphicon-remove' >";
                                } ?>

                            </td>

                            <td>  <?php
                                if ($user_role == 1 || $user_role == 3) { ?>
                                <a title="Azuriraj zadatak" href="edit-task.php?task_id=<?php
                                echo $row['task_id']; ?>"><span class="glyphicon glyphicon-edit"></span></a><?php
                                } ?>&nbsp;&nbsp;

                                <a title="Komentarisi" href="comment.php?t_title=<?php
                                echo $row['t_title']; ?>&com_userid=<?php
                                echo $user_id ?>"><span class="glyphicon glyphicon-edit"></span></a> &nbsp;&nbsp;
                                <a title="Pogledaj komentare" href="Comments.php?task_title=<?php
                                echo $row['t_title']; ?>"><span class="glyphicon glyphicon-folder-open"></span></a>&nbsp;&nbsp;

                                <?php
                                if ($user_role == 1 || $user_role == 3){ ?>

                                <a title="Obrisi zadatak" href="?delete_task=delete_task&t_title=<?php
                                echo $row['t_title']; ?>" onclick=" return check_delete();"><span
                                            class="glyphicon glyphicon-trash"></span></a></td>
                        <?php
                        } ?>
                        </tr>
                        <?php
                    } ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php

include("include/footer.php");


?>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">


    flatpickr('#t_end_time', {
        enableTime: true
    });

</script>
