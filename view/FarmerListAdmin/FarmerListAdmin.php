<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "FarmerListAdmin";
include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
include_once("./search.php");

$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
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
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อเกษตรกร</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายชื่อเกษตรกร</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <?php
        creatCard("card-color-one",   "จำนวนเกษตรกร", getcountFarmer() . " คน", "waves");
        ?>

        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-four shadow h-100 py-2" id="addUser" style="cursor:pointer;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">เพิ่มเกษตรกร</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">+1 คน</div>
                        </div>
                        <div class="col-auto">
                            <i class="material-icons icon-big">add_location</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="FarmerListAdmin.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" <?php
                                                                                                                                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                                                                                                                                    echo 'aria-expanded="true"';
                                                                                                                                else
                                                                                                                                    echo 'aria-expanded="false"';
                                                                                                                                ?> aria-controls="collapseOne" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" <?php
                                        if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                                            echo 'class="collapse show"';
                                        else
                                            echo 'class="collapse"';
                                        ?> aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-body" style="background-color: white; ">
                        <div class="row mb-4 ">
                            <div class="col-xl-4 col-12 text-right">
                                <span>หมายเลขบัตรประชาชน</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <input type="password" class="form-control input-setting" id="s_formalid" name="s_formalid" <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                <i class="far fa-eye-slash eye-setting"></i>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>ชื่อเกษตรกร</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <input type="text" class="form-control" id="s_name" name="s_name" <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>จังหวัด</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <select id="s_province" name="s_province" class="form-control">
                                    <option selected value=0>เลือกจังหวัด</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                        if ($fpro == $PROVINCE[$i]["AD1ID"])
                                            echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '" selected>' . $PROVINCE[$i]["Province"] . '</option>';
                                        else
                                            echo '<option value="' . $PROVINCE[$i]["AD1ID"] . '">' . $PROVINCE[$i]["Province"] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>อำเภอ</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <select id="s_distrinct" name="s_distrinct" class="form-control">
                                    <option selected value=0>เลือกอำเภอ</option>>
                                    <?php
                                    if ($fpro != 0) {
                                        for ($i = 1; $i < sizeof($DISTRINCT_PROVINCE); $i++) {
                                            if ($fdist == $DISTRINCT_PROVINCE[$i]["AD2ID"])
                                                echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '" selected>' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                            else
                                                echo '<option value="' . $DISTRINCT_PROVINCE[$i]["AD2ID"] . '">' . $DISTRINCT_PROVINCE[$i]["Distrinct"] . '</option>';
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                            </div>
                            <div class="col-xl-6 col-12">
                                <button type="submit" id="btn_pass" class="btn btn-success btn-sm form-control">ค้นหา</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header card-header-table py-3">
            <h6 class="m-0 font-weight-bold" style="color:#006633;">รายชื่อเกษตรกรในระบบ</h6>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>หมายเลขบัตรประชาชน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อำเภอ</th>
                            <th>จังหวัด</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>หมายเลขบัตรประชาชน</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อำเภอ</th>
                            <th>จังหวัด</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        for ($i = 1; $i < sizeof($FARMER); $i++) {
                            $fid = $FARMER[$i]["FormalID"];
                            $formalid = substr_replace($fid, "xxxxxxx", 3, 7);
                        ?>
                            <tr>
                                <td align="center"><?php echo $formalid; ?></td>
                                <td><?php echo $FARMER[$i]["FirstName"]; ?> <?php echo $FARMER[$i]["LastName"]; ?></td>
                                <td><?php echo $FARMER[$i]["Distrinct"]; ?></td>
                                <td><?php echo $FARMER[$i]["Province"]; ?></td>
                                <td style="text-align:center;">
                                    <?php
                                    $isBlock = $FARMER[$i]["IsBlock"];
                                    $uid = $FARMER[$i]["UFID"];
                                    if ($isBlock == NULL) {
                                        echo "<a href='manage.php?confirm=1&uid=$uid'>กดเพื่อยืนยัน</a>";
                                    } else {
                                        echo "<label>ยืนยันแล้ว</label>";
                                    }

                                    ?>

                                </td>

                                <td style="text-align:center;">
                                    <?php
                                    if ($FARMER[$i]["IsBlock"] == 0) {
                                        echo "<button type='button' data-toggle='tooltip' title='บล็อค' class='btn btn-success btn-sm tt' ";
                                    } else {
                                        echo "<button type='button' data-toggle='tooltip' title='ปลดบล็อค' class='btn btn-danger btn-sm tt' ";
                                    }
                                    ?> id="<?php echo $FARMER[$i]["UFID"] ?>" onclick="
                                    <?php if ($FARMER[$i]["IsBlock"] == 0) {
                                        echo "block";
                                    } else {
                                        echo "unblock";
                                    }
                                    ?>
                                    ('<?php echo $FARMER[$i]["FirstName"]; ?>' ,'<?php echo $FARMER[$i]["LastName"]; ?>',
                                    '<?php echo $FARMER[$i]["UFID"] ?>')">
                                    <i class="fas fa-ban"></i></button>

                                    <button type="button" class="btn btn-warning btn-sm btn_edit tt" data-toggle="tooltip" title="แก้ไขข้อมูล" uid="<?php echo $FARMER[$i]["UFID"]; ?>" titles="<?php echo $FARMER[$i]["Title"]; ?>" formalid="<?php echo $formalid; ?>" fname="<?php echo $FARMER[$i]["FirstName"]; ?>" lname="<?php echo $FARMER[$i]["LastName"]; ?>" idline="<?php echo $FARMER[$i]["IdLine"]; ?>" mail="<?php echo $FARMER[$i]["EMAIL"]; ?>" type_email="<?php echo $FARMER[$i]["ETID"]; ?>" address="<?php echo $FARMER[$i]["Address"]; ?>" province="<?php echo $FARMER[$i]["AD1ID"]; ?>" distrinct="<?php echo $FARMER[$i]["AD2ID"]; ?>" subdistrinct="<?php echo $FARMER[$i]["AD3ID"]; ?>">
                                        <i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm tt" data-toggle="tooltip" title="ลบ" onclick="delfunction('<?php echo $FARMER[$i]["FirstName"]; ?>','<?php echo $FARMER[$i]["LastName"]; ?>', '<?php echo $FARMER[$i]["UFID"] ?>')">
                                        <i class="fas fa-trash-alt"></i></button>



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
<?php include_once("FarmerListAdminModal.php"); ?>

<script src="FarmerListAdmin.js"></script>