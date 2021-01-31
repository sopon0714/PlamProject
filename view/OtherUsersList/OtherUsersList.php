<?php
session_start();
$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "OtherUsersList";
include_once("./../layout/LayoutHeader.php");
include_once("./../../query/query.php");
include_once("./search.php");
$DEPARTMENT = getDepartment();
?>


<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อผู้ใช้</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">รายชื่อผู้ใช้</a>
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
        creatCard("card-color-two",   "ผู้ใช้งานทั้งหมด", getcountUser() . " คน", "dashboard");
        creatCard("card-color-three",   "ผู้ดูแลระบบ", getcountAdmin() . " คน", "format_size");
        ?>

        <div class="col-xl-3 col-12 mb-4">
            <div class="card border-left-primary card-color-four shadow h-100 py-2" id="addUser" style="cursor:pointer;">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="font-weight-bold  text-uppercase mb-1">เพิ่มผู้ใช้งานใหม่</div>
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

    <form action="OtherUsersList.php?isSearch=1" method="post">
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

                    <div class="card-body" style="background-color: white;">
                        <div class="row mb-4 ">
                            <div class="col-xl-4 col-12 text-right">
                                <span>หน่วยงาน</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <select class="form-control" id="s_department" name="s_department">
                                    <option value="0">ทุกหน่วยงาน</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($DEPARTMENT); $i++) {
                                        if ($department == $DEPARTMENT[$i]["DID"])
                                            echo '<option value="' . $DEPARTMENT[$i]["DID"] . '" selected>' . $DEPARTMENT[$i]["Department"] . '</option>';
                                        else
                                            echo '<option value="' . $DEPARTMENT[$i]["DID"] . '">' . $DEPARTMENT[$i]["Department"] . '</option>';
                                    } ?>
                                </select>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>สิทธิการเข้าใช้งาน</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_admin" name="s_admin" <?php if ($admin == 1) echo ' checked ' ?> value="option1">
                                    <label class="form-check-label" for="inlineCheckbox1">ผู้ดูแลระบบ</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_admin2" name="s_admin2" <?php if ($admin2 == 1) echo ' checked ' ?> value="option1">
                                    <label class="form-check-label" for="inlineCheckbox1">ผู้ช่วยผู้ดูแลระบบ</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_research" name="s_research" <?php if ($research == 1) echo ' checked ' ?> value="option2">
                                    <label class="form-check-label" for="inlineCheckbox2">นักวิจัย</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_operator" name="s_operator" <?php if ($operator == 1) echo ' checked ' ?> value="option3">
                                    <label class="form-check-label" for="inlineCheckbox3">พนักงานทั่วไป</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_farmer" name="s_farmer" <?php if ($farmer == 1) echo ' checked ' ?> value="option4">
                                    <label class="form-check-label" for="inlineCheckbox4">เกษตรกร</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-4 col-12 text-right">
                                <span>การบล็อกการเข้าใช้งานของผู้ใช้</span>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_block" name="s_block" <?php if ($block == 1) echo ' checked ' ?> value="1">
                                    <label class="form-check-label" for="inlineCheckbox1">บล็อค</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="s_unblock" name="s_unblock" <?php if ($unblock == 1) echo ' checked ' ?> value="0">
                                    <label class="form-check-label" for="inlineCheckbox2">ไม่บล็อก</label>
                                </div>

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
            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">รายชื่อผู้ใช้ในระบบ</span>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>บัญชีชื่อผู้ใช้</th>
                            <th>อีเมล์</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>หน่วยงาน</th>
                            <th>สิทธิการเข้าใช้งาน</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>บัญชีชื่อผู้ใช้</th>
                            <th>อีเมล์</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>หน่วยงาน</th>
                            <th>สิทธิการเข้าใช้งาน</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        for ($i = 1; $i < sizeof($USER); $i++) {
                        ?>
                            <tr>
                                <td><?php echo $USER[$i]["UserName"]; ?></td>
                                <td><?php echo $USER[$i]["EMAIL"]; ?>@<?php echo $USER[$i]["Type"]; ?></td>
                                <td><?php echo $USER[$i]["FirstName"]; ?> <?php echo $USER[$i]["LastName"]; ?></td>
                                <td><?php echo $USER[$i]["Department"]; ?></td>
                                <td style="text-align:center;">
                                    <?php if ($USER[$i]["IsAdmin"]) { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="แอดมิน" class="btn btn-success btn-sm btn-circle tt">A</button>
                                    <?php } else { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="แอดมิน" class="btn btn-secondary btn-sm btn-circle tt">A</button>
                                    <?php } ?>
                                    <?php if ($USER[$i]["IsAdmin2"]) { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="แอดมิน2" class="btn btn-success btn-sm btn-circle tt">A2</button>
                                    <?php } else { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="แอดมิน2" class="btn btn-secondary btn-sm btn-circle tt">A2</button>
                                    <?php } ?>
                                    <?php if ($USER[$i]["IsResearch"]) { ?>
                                        <button type="button" id="btn_info" data-toggle="tooltip" title="นักวิจัย" class=" btn btn-success btn-sm btn-circle tt">R</button>
                                    <?php } else { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="นักวิจัย" class="btn btn-secondary btn-sm btn-circle tt">R</button>
                                    <?php } ?>
                                    <?php if ($USER[$i]["IsOperator"]) { ?>
                                        <button type="button" id="btn_delete" data-toggle="tooltip" title="พนักงานทั่วไป" class="btn btn-success btn-sm btn-circle tt">O</button>
                                    <?php } else { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="พนักงานทั่วไป" class="btn btn-secondary btn-sm btn-circle tt">O</button>
                                    <?php } ?>
                                    <?php if ($USER[$i]["IsFarmer"]) { ?>
                                        <button type="button" id="btn_delete" data-toggle="tooltip" title="เกษตรกร" class="btn btn-success btn-sm btn-circle tt">F</button>
                                    <?php } else { ?>
                                        <button type="button" id="btn_comfirm" data-toggle="tooltip" title="เกษตรกร" class="btn btn-secondary btn-sm btn-circle tt">F</button>
                                    <?php } ?>

                                </td>

                                <td style="text-align:center;">
                                    <!-- <button type="button" data-toggle="tooltip" title="บล็อค"  -->
                                    <?php
                                    if ($USER[$i]["IsBlock"] == 0) {
                                        echo "<button type='button' data-toggle='tooltip' title='บล็อค' class='btn btn-success btn-sm tt' ";
                                    } else {
                                        echo "<button type='button' data-toggle='tooltip' title='ปลดบล็อค' class='btn btn-danger btn-sm tt' ";
                                    }
                                    ?> id="<?php echo $USER[$i]["UID"] ?>" onclick="
                                    <?php if ($USER[$i]["IsBlock"] == 0) {
                                        echo "block";
                                    } else {
                                        echo "unblock";
                                    }
                                    ?>
                                    ('<?php echo $USER[$i]["UserName"]; ?>' , '<?php echo $USER[$i]["UID"] ?>')">
                                    <i class="fas fa-ban"></i></button>

                                    <button type="button" class="btn btn-info btn-sm pass_edit tt" data-toggle="tooltip" title="แก้ไขรหัสผ่าน" uid="<?php echo $USER[$i]["UID"]; ?>" username="<?php echo $USER[$i]["UserName"]; ?>" pass="<?php echo $USER[$i]["PWD"]; ?>" titles="<?php echo $USER[$i]["Title"]; ?>" fname="<?php echo $USER[$i]["FirstName"]; ?>" lname="<?php echo $USER[$i]["LastName"]; ?>">
                                        <i class="fas fa-lock"></i></button>

                                    <button type="button" class="btn btn-warning btn-sm btn_edit tt" data-toggle="tooltip" title="แก้ไขข้อมูล" uid="<?php echo $USER[$i]["UID"]; ?>" titles="<?php echo $USER[$i]["Title"]; ?>" username="<?php echo $USER[$i]["UserName"]; ?>" fname="<?php echo $USER[$i]["FirstName"]; ?>" lname="<?php echo $USER[$i]["LastName"]; ?>" idline="<?php echo $USER[$i]["IdLine"]; ?>" mail="<?php echo $USER[$i]["EMAIL"]; ?>" type_email="<?php echo $USER[$i]["ETID"]; ?>" department="<?php echo $USER[$i]["DID"]; ?>" admin="<?php echo $USER[$i]["IsAdmin"]; ?>" admin2="<?php echo $USER[$i]["IsAdmin2"]; ?>" research="<?php echo $USER[$i]["IsResearch"]; ?>" operator="<?php echo $USER[$i]["IsOperator"]; ?>" farmer="<?php echo $USER[$i]["IsFarmer"]; ?> ">
                                        <i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-sm tt" data-toggle="tooltip" title="ลบ" onclick="delfunction('<?php echo $USER[$i]["UserName"]; ?>' , '<?php echo $USER[$i]["UID"] ?>')">
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
<?php include_once("OtherUsersListModal.php"); ?>

<script src="OtherUsersList.js"></script>