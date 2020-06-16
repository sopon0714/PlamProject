<!------------ Start Modal ------------>

<div class="modal fade" id="modal-1">
    <form method="post" id="formAddRain" name="formAddRain" action="manage.php">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                    <h4 class="modal-title">เพิ่มปริมาณฝนตก</h4>
                </div>
                <div class="modal-body" id="addModalBody1">

                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>วันที่</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="date" name="dateRain" class="form-control" id="dateRian" required="" oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <input type="hidden" name="FarmIDRian" value="<?= $INFOSUBFARM[1]['DIMfarmID'] ?>">
                    <input type="hidden" name="SubFarmIDRian" value="<?= $INFOSUBFARM[1]['DIMSubfarmID'] ?>">
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>เวลาที่ฝนเริ่มตก</span>
                        </div>
                        <div class="col-xl-5 col-12 ">
                            <input type="time" id="timeStratRian" name="timeStratRian" class="form-control " required="" oninput="setCustomValidity('')" />

                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>เวลาที่ฝนหยุดตก</span>
                        </div>
                        <div class="col-xl-5 col-12 ">
                            <input type="time" id="timeEndRian" name="timeEndRian" class="form-control" required="" oninput="setCustomValidity('')" />
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ประเภทปริมาณน้ำ</span>
                        </div>
                        <div class="col-xl-5 col-12 ">
                            <input type="radio" id="Type" name="Type" class="radioType" checked value="1">
                            <label for="male">ตามระดับความรุนแรง</label><br>
                            <input type="radio" name="Type" class="radioType" value="2">
                            <label for="female">ตามปริมาณฝน</label><br>
                        </div>
                    </div>
                    <div class="row mb-4 show1">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ระดับฝนตก</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <select class="form-control" id="rankRain" name="rankRain" required="" oninput="setCustomValidity('')">
                                <option selected value="0">เลือกระดับปริมาณฝน</option>
                                <option value="5.00">เบา (ปริมาณ 0.1 มม. ถึง 10.0 มม.)</option>
                                <option value="22.50">ปานกลาง (ปริมาณ 10.1 มม. ถึง 35.0 มม.)</option>
                                <option value="62.50">หนัก (ปริมาณ 35.1 มม. ถึง 90.0 มม.)</option>
                                <option value="110.00">หนักมาก (ปริมาณ 90.1 มม. ขึ้นไป)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-4 show2">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ปริมาณน้ำฝน (มม.)</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="number" id="rainVol" name="rainVol" class="form-control text-right" required="" oninput="setCustomValidity('')" min="0" step="0.01" value="0">
                        </div>
                    </div>
                    <input type="text" hidden name="FSID" value="<?= $INFOSUBFARM[1]['FSID'] ?>">
                    <input type="text" hidden name="TypeDetail" value="Detail">
                    <input type="text" hidden name="action" value="AddRain">
                </div>
                <div class="modal-footer">
                    <button id="btn-submitRain" type="submit" class="btn btn-success">ยืนยัน</button>
                    <button id="m_not_success1" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="modal-2">
    <form method="post" id="formAddWater" name="formAddWater" action="manage.php">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                    <h4 class="modal-title">เพิ่มระบบการให้น้ำ</h4>
                </div>
                <div class="modal-body" id="addModalBody2">

                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>วันที่</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="date" id="dateWater" name="dateWater" class="form-control" required="" oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <input type="hidden" name="FarmIDWater" value="<?= $INFOSUBFARM[1]['DIMfarmID'] ?>">
                    <input type="hidden" name="SubFarmIDWater" value="<?= $INFOSUBFARM[1]['DIMSubfarmID'] ?>">
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>เวลาที่เริ่มให้น้ำ</span>
                        </div>
                        <div class="col-xl-5 col-12 ">
                            <input type="time" id="timeStratWater" name="timeStratWater" class="form-control " required="" oninput="setCustomValidity('')" />

                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>เวลาที่หยุดให้น้ำ</span>
                        </div>
                        <div class="col-xl-5 col-12 ">
                            <input type="time" id="timeEndWater" name="timeEndWater" class="form-control" required="" oninput="setCustomValidity('')" />
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-xl-4 col-12 text-right">
                            <span>ปริมาณการให้น้ำ (ลิตร)</span>
                        </div>
                        <div class="col-xl-5 col-12">
                            <input type="number" class="form-control text-right" min="0" value="0" step="0.01" id="waterVol" name="waterVol" required="" oninput="setCustomValidity('')">
                        </div>
                    </div>
                    <input type="text" hidden name="FSID" value="<?= $INFOSUBFARM[1]['FSID'] ?>">
                    <input type="text" hidden name="TypeDetail" value="Detail">
                    <input type="text" hidden name="action" value="AddWater">
                </div>
                <div class="modal-footer">
                    <button id="btn-submitWater" type="submit" class="btn btn-success">ยืนยัน</button>
                    <button id="m_not_success2" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </div>
    </form>
</div>