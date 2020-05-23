<?php
$PROVINCE = getProvince();
$FARMER = getAllFarmer();
?>

<div class="modal fade" id="addModal">
    <form action="./manage.php" method="post" enctype="multipart/form-data" id="form-insert">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class=" modal-title">เพิ่มสวนปาล์ม</h4>
                </div>
                <div class="modal-body" id="addModalBody">
                    <form action="#" method="post">
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="namefarm" required="" oninput="setCustomValidity(' ')" placeholder="กรุณากรอกชื่อสวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ชื่อย่อสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="aliasfarm" required="" oninput="setCustomValidity(' ')" placeholder="กรุณากรอกชื่อย่อสวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ที่อยู่ </span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <input type="text" class="form-control" name="addfarm" required="" oninput="setCustomValidity(' ')" placeholder="กรุณากรอกที่อยู่สวนปาล์ม">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right">
                                <span>จังหวัด<span class="text-danger"> *</span></span>
                            </div>
                            <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                                <select id="province" name="province" class="form-control">
                                    <option selected value='0' disabled=""> เลือกจังหวัด</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($PROVINCE); $i++) {
                                    ?>
                                        <option value="<?php echo $PROVINCE[$i]["AD1ID"]; ?>"> <?php echo $PROVINCE[$i]["Province"]; ?> </option>
                                    <?php
                                    }
                                    ?>
                                </select>

                            </div>

                        </div>

                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>อำเภอ</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select name="distrinct" class="form-control" id="distrinct">
                                    <option selected value=0 disabled="">เลือกอำเภอ</option>

                                </select>

                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>ตำบล</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select name="subdistrinct" id="subdistrinct" class="form-control">
                                    <option selected value=0 disabled="">เลือกตำบล</option>

                                </select>
                            </div>
                        </div>


                        <div class="row mb-4">
                            <div class="col-xl-3 col-12 text-right">
                                <span>เจ้าของสวนปาล์ม</span>
                            </div>
                            <div class="col-xl-9 col-12">
                                <select class="form-control" id="farmer" name="farmer">
                                    <option selected disabled="" value="0">เลือกเจ้าของสวน</option>
                                    <?php
                                    for ($i = 1; $i < sizeof($FARMER); $i++) {
                                        echo "<option value=\"{$FARMER[$i]['UFID']}\">{$FARMER[$i]['FirstName']} {$FARMER[$i]['LastName']}</option>";
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="add">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success btn-md insertFarm" style="float:right;">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>

            </div>
        </div>
    </form>
</div>