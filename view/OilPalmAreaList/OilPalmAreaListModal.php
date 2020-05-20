<?php
$PROVINCE = getProvince();
$FARMER = getAllFarmer();
?>

<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="form-signin" method="POST" action='manage.php'>
                <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                    <h4 class=" modal-title">เพิ่มสวนปาล์ม</h4>
                </div>
                <div class="modal-body" id="addModalBody">
                    <div class="row mb-4">
                        <div class="col-xl-3 col-12 text-right">
                            <span>ชื่อสวนปาล์ม</span>
                        </div>
                        <div class="col-xl-9 col-12">
                            <input type="text" class="form-control" name="namefarm" id="rank3">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-3 col-12 text-right">
                            <span>ชื่อย่อสวนปาล์ม</span>
                        </div>
                        <div class="col-xl-9 col-12">
                            <input type="text" class="form-control" name="aliasfarm" id="rank4">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-3 col-12 text-right">
                            <span>ที่อยู่</span>
                        </div>
                        <div class="col-xl-9 col-12">
                            <input type="text" class="form-control" name="addfarm" id="rrr">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 text-right">
                            <span>จังหวัด<span class="text-danger"> *</span></span>
                        </div>
                        <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
                            <select id="province" name="province" class="form-control">
                                <option selected value=0 disabled=""> เลือกจังหวัด</option>
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
                                <option selected="" disabled="">เลือกอำเภอ</option>

                            </select>

                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-3 col-12 text-right">
                            <span>ตำบล</span>
                        </div>
                        <div class="col-xl-9 col-12">
                            <select name="subdistrinct" id="subdistrinct" class="form-control">
                                <option selected="" disabled="">เลือกตำบล</option>

                            </select>
                        </div>
                    </div>


                    <div class="row mb-4">
                        <div class="col-xl-3 col-12 text-right">
                            <span>เจ้าของสวนปาล์ม</span>
                        </div>
                        <div class="col-xl-9 col-12">
                            <select class="form-control" id="farmer" name="farmer">
                                <option selected="" disabled="">เลือกเจ้าของสวน</option>
                                <?php
                                for ($i = 1; $i < sizeof($FARMER); $i++) {
                                    echo "<option value=\"{$FARMER[$i]['UFID']}\">{$FARMER[$i]['FirstName']} {$FARMER[$i]['LastName']}</option>";
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="add">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-success btn-md" style="float:right;" type="submit">ยืนยัน</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </form>
        </div>
    </div>
</div>