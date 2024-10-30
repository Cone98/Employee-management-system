<?php

require 'authentication.php'; // admin authentication check 

// auth check
$user_id = $_SESSION['admin_id'];
$user_name = $_SESSION['name'];
$security_key = $_SESSION['security_key'];
$user_role = $_SESSION['user_role'];
if ($user_id == null || $security_key == null) {
    header('Location: index.php');
}


if (isset($_GET['delete_comment'])) {
    $comm_id = $_GET['com_id'];

    $con = mysqli_connect("Localhost", "root", "", "projekat");
    if (mysqli_connect_errno()) {
        echo "Connection Fail" . mysqli_connect_error();
    }
    $ret = mysqli_query($con, "DELETE FROM comments WHERE ID = '$comm_id'");
}
if (isset($_GET['task_title'])) {
    $task_title = $_GET['task_title'];
}


$page_name = "Comment";
include("include/sidebar.php");


?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


<div class="row">
    <div class="col-md-12">
        <div class="well well-custom">
            <div class="row">
                <div class="col-md-8 ">
                    <div class="btn-group">

                    </div>
                </div>

            </div>

            <center><h3>Komentari</h3></center>
            <div class="gap"></div>

            <div class="gap"></div>

            <div class="table-responsive">
                <table class="table table-codensed table-custom">
                    <thead>
                    <tr>
                        <th>Ime.</th>
                        <th>Naslov</th>
                        <th>Komentar</th>
                        <th>Zadatak</th>
                        <?php
                        if ($user_role == 1 || $user_role == 3) { ?>
                            <th>Akcija</th>
                        <?php
                        } ?>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    $sql = "SELECT c.*, a.fullname 
				  FROM comments c JOIN tbl_admin a on(c.user_id=a.user_id)
				  WHERE task_titl = '$task_title'";


                    $info = $obj_admin->manage_all_info($sql);
                    $serial = 1;
                    $num_row = $info->rowCount();
                    if ($num_row == 0) {
                        echo '<tr><td colspan="7">Nema komentara na ovom zadatku</td></tr>';
                    }
                    while ($row = $info->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?php
                                echo $row['fullname']; ?></td>
                            <td><?php
                                echo $row['title']; ?></td>
                            <td><?php
                                echo $row['description']; ?></td>
                            <td><?php
                                echo $row['task_titl']; ?></td>


                            <?php
                            if ($user_role == 1) { ?>
                                <td>
                                    <a title="Delete" href="?delete_comment=delete_comment&com_id=<?php
                                    echo $row['ID']; ?>&task_title=<?php
                                    echo $row['task_titl']; ?>" onclick=" return check_delete();"><span
                                                class="glyphicon glyphicon-trash"></span></a>
                                </td>
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
