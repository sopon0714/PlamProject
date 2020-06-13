<!------------ Start Modal ------------>

<div class="modal" id="modal-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal">
                <h4 class="modal-title">เพิ่มปริมาณฝนตก</h4>
            </div>
            <div class="modal-body" id="addModalBody1">

                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>วันที่</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <div class="input-group">
                            <input type="text" class="form-control" data-toggle="datepicker" id="r_date1" name="r_date1">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากสวน</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_farm1">
                            <option disabled selected>เลือกสวน</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากแปลง</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_subfarm1">
                            <option disabled selected>เลือกแปลง</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่ฝนเริ่มตก</span>
                    </div>
                    <div class="col-xl-9 col-12 ">
                        <input type="text" id="r1_timepicker1" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่ฝนหยุดตก</span>
                    </div>
                    <div class="col-xl-9 col-12 timepicker">
                        <input type="text" id="r2_timepicker1" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ระดับฝนตก</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="form-control" id="r_rank1">
                            <option disabled selected value="null">เลือกระดับปริมาณฝน</option>
                            <option value="1">เบา (ปริมาณ 0.1 มม. ถึง 10.0 มม.)</option>
                            <option value="2">ปานกลาง (ปริมาณ 10.1 มม. ถึง 35.0 มม.)</option>
                            <option value="3">หนัก (ปริมาณ 35.1 มม. ถึง 90.0 มม.)</option>
                            <option value="4">หนักมาก (ปริมาณ 90.1 มม. ขึ้นไป)</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ปริมาณน้ำฝน / ลิตร</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <input type="number" class="form-control" id="r_raining1">
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button id="m_success1" type="button" class="btn btn-success" data-dismiss="modal">ยืนยัน</button>
                <button id="m_not_success1" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-2">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal">
                <h4 class="modal-title">เพิ่มระบบการให้น้ำ</h4>
            </div>
            <div class="modal-body" id="addModalBody2">

                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>วันที่</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <div class="input-group">
                            <input type="text" class="form-control" data-toggle="datepicker" id="r_date2" name="r_date2">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary docs-datepicker-trigger" disabled>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากสวน</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_farm2">
                            <option disabled selected>เลือกสวน</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>จากแปลง</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <select class="js-example-basic-single" id="r_subfarm2">
                            <option disabled selected>เลือกแปลง</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่เริ่มให้น้ำ</span>
                    </div>
                    <div class="col-xl-9 col-12  timepicker">
                        <input type="text" id="r1_timepicker2" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>เวลาที่หยุดให้น้ำ</span>
                    </div>
                    <div class="col-xl-9 col-12  timepicker">
                        <input type="text" id="r2_timepicker2" class="form-control MDTimepicker" />
                        <span class="customSetPosition-Icon"><i class="far fa-clock"></i></span>
                    </div>
                </div>
                <!-- <div class="row mb-4">
                    <div class="col-xl-3 col-12 text-right">
                        <span>ปริมาณการให้น้ำ / ลิตร</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <input type="number" class="form-control" id="r_raining2">
                    </div>
                </div> -->

            </div>
            <div class="modal-footer">
                <button id="m_success2" type="button" class="btn btn-success" data-dismiss="modal">ยืนยัน</button>
                <button id="m_not_success2" type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
            </div>
        </div>
    </div>
</div>