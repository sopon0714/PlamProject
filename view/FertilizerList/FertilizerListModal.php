<?php
session_start();
include_once("./../../query/query.php");
$MAINNUTR = getMainNutr();
$SUBNUTR = getSubNutr();
?>
<div class="modal fade" id="detailNutrModel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header header-modal" style="background-color: <?= $color ?>;">
                <h4 class=" modal-title">รายละเอียดธาตุอาหาร</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="addModalBody">
                <div class="row mb-4">
                    <div class="col-xl-2 col-12 text-right">
                        <span>ประเภท :</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <span id="titleDetail">ธาตุอาหารหลัก</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-xl-2 col-12 text-right">
                        <span>รายละเอียด :</span>
                    </div>
                    <div class="col-xl-9 col-12">
                        <table class="table  table-bordered table-data" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ชื่อธาตุอาหาร</th>
                                    <th>อัตราส่วน(%)</th>

                                </tr>
                            </thead>

                            <tbody id="bodyDetailNutr">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="insert" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" id="formAdd" name="formAdd" action="./manage.php">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header header-modal" style="background-color: <?= $color ?>">
                        <h4 class="modal-title">เพิ่มชนิดปุ๋ย</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="addModalBody">
                        <div class="main">

                            <div class="row mb-4 ">
                                <div class="col-xl-4 col-12 text-right">
                                    <span>ชื่อปุ๋ย</span>

                                </div>
                                <div class="col-xl-8 col-12">
                                    <input id='name' name='name' class='form-control col-8' required="" oninput="setCustomValidity('')" placeholder="ใส่ชื่อปุ๋ย">
                                </div>
                            </div>
                            <div class="row mb-4 ">
                                <div class="col-xl-4 col-12 text-right">
                                    <span>ชื่อย่อ</span>
                                </div>
                                <div class="col-xl-8 col-12">
                                    <input id='alias' name='alias' class='form-control col-8' required="" oninput="setCustomValidity('')" placeholder="ใส่ชื่อย่อ">
                                </div>
                            </div>
                            <div class="row mb-4 ">
                                <div class="col-xl-6 col-12 text-right">
                                    <span>สัดส่วนองค์ประกอบธาตุอาหาร</span>

                                </div>

                            </div>
                            <div class="row mb-4 ">
                                <div class="col-xl-4 col-12 text-right">
                                    <span>ธาตุอาหารหลัก:</span>
                                </div>
                            </div>
                            <?php
                            for ($i = 1; $i <= $MAINNUTR[0]['numrow']; $i++) {
                                echo "  <div class=\"row mb-4 \">
                                            <div class=\"col-xl-4 col-12 text-right\">
                                                <span>{$MAINNUTR[$i]['Name']}</span>
                                            </div>
                                            <div class=\"col-xl-4 col-12 \">
                                                <input type=\"hidden\" name='mainID[]' value=\"{$MAINNUTR[$i]['NID']}\">
                                                <input type=\"number\" name='main[]' class='form-control  text-right mainVol VolCheck' min=\"0\" max=\"100\" required=\"\" oninput=\"setCustomValidity('')\" step=\"any\" value=\"0\">

                                            </div>
                                            <div class=\"col-xl-1 col-12 \">
                                                <span>%</span>
                                            </div>
                                        </div>";
                            }

                            ?>
                            <div class="row mb-4 ">
                                <div class="col-xl-4 col-12 text-right">
                                    <span>ธาตุอาหารรอง:</span>
                                </div>
                            </div>
                            <?php
                            for ($i = 1; $i <= $SUBNUTR[0]['numrow']; $i++) {
                                echo "  <div class=\"row mb-4 \">
                                            <div class=\"col-xl-4 col-12 text-right\">
                                                <span>{$SUBNUTR[$i]['Name']}</span>
                                            </div>
                                            <div class=\"col-xl-4 col-12 \">
                                                <input type=\"hidden\" name='subID[]' value=\"{$SUBNUTR[$i]['NID']}\">
                                                <input type=\"number\" name='sub[]' class='form-control  text-right SubVol VolCheck' min=\"0\"  max=\"100\" required=\"\" oninput=\"setCustomValidity('')\"  step=\"any\" value=\"0\">
                                            </div>
                                            <div class=\"col-xl-1 col-12 \">
                                                <span>%</span>
                                            </div>
                                        </div>";
                            }

                            ?>
                            <input type="hidden" name='action' value="insert">
                            <div class="modal-footer normal-button ">
                                <button type="submit" name="save" id="save" class="btn btn-success insertNutr">ยืนยัน</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">ยกเลิก</button>
                            </div>

                        </div>
                    </div>
                </div>
        </form>
    </div>
    <!-- end dialog -->
</div>