<?php


require 'authentication.php'; // admin authentication check 


$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
if ($user_id == null || $security_key == null) {
    header('Location: index.php');
}


$user_role = $_SESSION['user_role'];


if (isset($_GET['delete_task'])) {
    $task_titlr = $_GET['t_title'];
    $con = mysqli_connect("Localhost", "root", "", "projekat");
    if (mysqli_connect_errno()) {
        echo "Connection Fail" . mysqli_connect_error();
    }
    $ret = mysqli_query($con, "DELETE FROM task_info WHERE t_title = '$task_titlr'");
    $ret = mysqli_query($con, "DELETE FROM comments WHERE task_titl = '$task_titlr'");


    header("Location: task-info.php");
}

if (isset($_POST['add_task_post'])) {
    $obj_admin->add_new_task($_POST);
}


if (isset($_POST['asigneessPost'])) {
    $Employees = $_POST['assignees_to'];
    header("Location: task-info1.php ?Employees=$Employees");
}


if (isset($_POST['file_upload'])) {
// Count total uploaded files
    $totalfiles = count($_FILES['file']['name']);

// Looping over all files
    for ($i = 0; $i < $totalfiles; $i++) {
        $filename = $_FILES['file']['name'][$i];

// Upload files and store in database
        if (move_uploaded_file($_FILES["file"]["tmp_name"][$i], 'upload/' . $filename)) {
// Image db insert sql
            $insert = "INSERT into files(file_name,uploaded_on,task) values('$filename',now(),1)";
            if (mysqli_query($conn, $insert)) {
                echo 'Data inserted successfully';
            } else {
                echo 'Error: ' . mysqli_error($conn);
            }
        } else {
            echo 'Error in uploading file - ' . $_FILES['file']['name'][$i] . '<br/>';
        }
    }
}


if (isset($_POST['task_odDo'])) {
    $time1 = $_POST['t_time_od'];
    $time2 = $_POST['t_time_do'];
    header("Location: task-info1.php?time_od=$time1&time_do=$time2 ");
}


if (isset($_POST['add_taskgroup_post'])) {
    $obj_admin->add_new_task_group($_POST);
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
if (isset($_POST['titletask_post'])) {
    $titletask = $_POST['titletask'];
    header("Location: task-info1.php ?titletask=$titletask");
}

$page_name = "Task_Info";
include("include/sidebar.php");


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
                                    <label class="control-label col-sm-5">Rok izvrsenja</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="t_end_time" id="t_end_time" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Zaposleni</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sql = "SELECT user_id, fullname FROM tbl_admin WHERE user_role in (2, 3)";
                                        $info = $obj_admin->manage_all_info($sql);
                                        ?>
                                        <select name="assign_to[]" class="form-control multiple-select" multiple>


                                            <?php
                                            while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $row['user_id']; ?>"><?php
                                                    echo isset($assign_to) && in_array(
                                                        $row['user_id'],
                                                        explode(',', $assign_to)
                                                    ) ? "selected" : '' ?><?php
                                                    echo $row['fullname']; ?></option>
                                                <?php
                                            } ?>

                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Rukovodilac</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sql = "SELECT user_id, fullname FROM tbl_admin WHERE user_role in (1,3)";
                                        $info = $obj_admin->manage_all_info($sql);
                                        ?>
                                        <select class="form-control" name="assign_ruk" id="aassign_ruk" required>

                                            <?php
                                            while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $row['user_id']; ?>" <?php
                                                if ($user_id == $row['user_id']) { ?> selected <?php
                                                } ?>><?php
                                                    echo $row['fullname']; ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-5">Grupa zadatka</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sql = "SELECT ID, name FROM taskgroup";
                                        $info = $obj_admin->manage_all_info($sql);
                                        ?>
                                        <select class="form-control" name="assign_group" id="aassign_group" required>
                                            <option value="">Izaberi grupu zadatka...</option>

                                            <?php
                                            while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $row['ID']; ?>"><?php
                                                    echo $row['name']; ?></option>
                                                <?php
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-5">Prioritet</label>
                                        <div class="col-sm-4">
                                            <select class="form-control" name="prioritetTask" id="prioritetTask"
                                                    required>
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
                                    <form method='post' action='#' enctype='multipart/form-data'>
                                        <label class="control-label col-sm-5">Propratni fajlovi</label>
                                        <div class="form-group">
                                            <input type="file" name="file[]" multiple>
                                        </div>

                                    </form>

                                    <form method='post' action='#' enctype='multipart/form-data'>

                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="add_task_post" class="btn btn-success-custom"> Kreiraj 
										zadatak
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
                                    <label class="control-label col-sm-5">Naziv grupe zadataka</label>
                                    <div class="col-sm-7">
                                        <input type="text" placeholder="Naziv grupe zadataka" id="taskgroup_title"
                                               name="taskgroup_title" list="expense" class="form-control" id="default"
                                               required>
                                    </div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-3 col-sm-3">
                                        <button type="submit" name="add_taskgroup_post" class="btn btn-success-custom">
                                            Kreiraj Grupu
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
                                    <label class="control-label col-sm-5">Naziv grupe zadataka</label>
                                    <div class="col-sm-7">
                                        <?php
                                        $sql = "SELECT ID, name FROM taskgroup";
                                        $info = $obj_admin->manage_all_info($sql);
                                        ?>
                                        <select class="form-control" name="remove_group" id="removee_group" required>
                                            <option value="">Izaberi grupu...</option>

                                            <?php
                                            while ($row = $info->fetch(PDO::FETCH_ASSOC)) { ?>
                                                <option value="<?php
                                                echo $row['ID']; ?>"><?php
                                                    echo $row['name']; ?></option>
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


                    </div>

                </div>


            </div>
            <center><h3>Sekcija zadataka</h3></center>
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
                        <th>Rok zavrsetka</th>
                        <th>Prioritet</th>
                        <th>Status</th>
                        <th>Akcija</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if ($user_role == 1 || $user_role == 3) {
                        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id)
						GROUP BY a.t_title
                        ORDER BY prioritet DESC";
                    } else {
                        $sql = "SELECT a.*, b.fullname, c.name, t.fullname as sef, COUNT(a.t_title) as num
                  FROM task_info a
                  INNER JOIN tbl_admin b ON(a.t_user_id = b.user_id) INNER JOIN taskgroup c ON(c.ID=a.taskgroup)
				  LEFT JOIN tbl_admin t ON(a.rukovodioc = t.user_id) 
                  WHERE a.t_user_id = $user_id
				  GROUP BY a.t_title
                  ORDER BY prioritet DESC";
                    }

                    $info = $obj_admin->manage_all_info($sql);
                    $serial = 1;
                    $num_row = $info->rowCount();
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
                            $sql1 = "SELECT b.fullname FROM task_info JOIN tbl_admin b ON(t_user_id = b.user_id) WHERE t_title = '$titl' ORDER BY task_id DESC;"
                            ?>
                            <?php
                            $info1 = $obj_admin->manage_all_info($sql1);
                            $num_row1 = $info1->rowCount();

                            ?>
                            <td>
                                <?php
                                while ($row1 = $info1->fetch(PDO::FETCH_ASSOC)) { ?>

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
