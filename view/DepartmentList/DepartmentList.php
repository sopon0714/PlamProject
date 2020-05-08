<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "DepartmentList";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");

$ALLDEPARTMENT = getAllDepartment();
?>


<style>
    #serach {
        background-color: #E91E63;
        color: white;
        float: right;
    }

    #card-detail {
        border-color: #E91E63;
        border-top: none;
    }
</style>

<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อหน่วยงาน</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายชื่อหน่วยงาน</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php
        creatCard("card-color-one",   "หน่วยงาน", getcountDepartment() . " หน่วยงาน", "waves");
        ?>
        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-two shadow h-100 py-2" id="addDept" style="cursor:pointer;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">เพิ่มหน่วยงานใหม่</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">+1 หน่วยงาน</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">dashboard</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header card-header-table py-3">
            <h6 class="m-0 font-weight-bold " style="color:#006633;">รายชื่อหน่วยงานในระบบ</h6>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ชื่อหน่วยงาน</th>
                            <th>ชื่อย่อ</th>
                            <th>หมายเหตุ</th>
                            <th>จำนวนคน</th>
                            <th>จัดการ</th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ชื่อหน่วยงาน</th>
                            <th>ชื่อย่อ</th>
                            <th>หมายเหตุ</th>
                            <th>จำนวนคน</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody>

                        <?php
                        for ($i = 1; $i < sizeof($ALLDEPARTMENT); $i++) {
                        ?>
                            <tr>
                                <td><?php echo $ALLDEPARTMENT[$i]["Department"]; ?></td>
                                <td align="center"><?php echo $ALLDEPARTMENT[$i]["Alias"]; ?></td>
                                <td><?php echo $ALLDEPARTMENT[$i]["Note"]; ?></td>
                                <td align="right">
                                    <?php if ($ALLDEPARTMENT[$i]["count_de"] == 0) {
                                        echo $ALLDEPARTMENT[$i]["count_de"] . "&nbsp;คน";
                                    } else { ?>
                                        <a href="../OtherUsersList/OtherUsersList.php?did=<?= $ALLDEPARTMENT[$i]["DID"] ?>"><?php echo $ALLDEPARTMENT[$i]["count_de"] . "&nbsp;คน"; ?></a>
                                    <?php } ?>
                                </td>
                                <td style="text-align:center;">

                                    <button type="button" class="btn btn-warning btn-sm btn_edit tt" did="<?php echo $ALLDEPARTMENT[$i]["DID"]; ?>" data-toggle="tooltip" title="แก้ไขข้อมูล" department="<?php echo $ALLDEPARTMENT[$i]["Department"]; ?>" alias="<?php echo $ALLDEPARTMENT[$i]["Alias"]; ?>" note="<?php echo $ALLDEPARTMENT[$i]["Note"]; ?>">
                                        <i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm tt" data-toggle="tooltip" title="ลบ" onclick="delfunction('<?php echo $ALLDEPARTMENT[$i]["Department"]; ?>','<?php echo $ALLDEPARTMENT[$i]["DID"]; ?>',
                        '<?php echo $ALLDEPARTMENT[$i]["Department"]; ?>','<?php echo $ALLDEPARTMENT[$i]["Alias"]; ?>','<?php echo $ALLDEPARTMENT[$i]["Note"]; ?>')">
                                        <i class="far fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php
                        }

                        ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="Modal">

    </div>

</div>


<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once("DepartmentListModal.php"); ?>

<script src="DepartmentList.js"></script>