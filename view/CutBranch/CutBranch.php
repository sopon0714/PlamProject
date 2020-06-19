<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "CutBranch";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fyear = 0;
$fmin = -1;
$fmax = -1;
$fpro = 0;
$fdist = 0;

$DATA = getCutBranch($idformal, $fullname, $fpro, $fdist ,$fyear ,$fmin ,$fmax);
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
if($fyear == 0){
    $year = $currentYear;
}else{
    $year = $fyear;
}

?>

<link rel="stylesheet" href="../../css/insect admin/readmore.css">
<link rel="stylesheet" href="../../css/insect admin/stylePest.css">

<style>
textarea {
    overflow-y: scroll;
    height: 100px;
    resize: vertical;
}

.img-reletive input[type=file] {
    cursor: pointer;
    width: 100px;
    height: 100px;
    font-size: 100px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}

.croppie-container .cr-boundary {
    width: 350px;
    height: 220px;
}

.upload-demo2 {
    width: 350px;
    height: 250px;
}

.img-gal {
    width: 150px;
    height: 100px;
    z-index: 5;
}

.set-button {
    width: 30px !important;
}
</style>

<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ล้างคอขวด</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">ล้างคอขวด</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <?php
        creatCard("card-color-one",   "จำนวนล้างคอขวดเฉลี่ย ปี $year", getAvgCutBranch($year) . " ครั้ง", "waves");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"]. " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ ".getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>

    </div>

    <form action="CutBranch.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne"
                            style="cursor:pointer; background-color: <?= $color ?>; color: white;">
                            <div class="row">
                                <div class="col-3">
                                    <i class="fas fa-search"> ค้นหาขั้นสูง</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseOne" class="card collapse 
                <?php
                if (isset($_GET['isSearch']) && $_GET['isSearch'] == 1)
                    echo "show";
                else
                    echo "";
                ?> 
                " aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-header card-bg">
                        ตำแหน่งแปลงการล้างคอขวด
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-12">
                                <div id="map" style="width:auto;height:68.1vh;"></div>
                            </div>
                            <div class="col-xl-6 col-12">
                                <div class="row">
                                    <div class="col-12">
                                        <span>ปี</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_year" name="s_year" class="form-control ">
                                            <option selected value=0>เลือกปี</option>
                                            <?php
                                                $yearCurrent = date('Y') + 543;
                                            for ($i = 0 ; $i <= 2; $i++, $yearCurrent--){
                                                if ($fyear == $yearCurrent)
                                                    echo '<option value="' . $yearCurrent . '" selected>' . $yearCurrent . '</option>';
                                                else
                                                    echo '<option value="' . $yearCurrent . '">' . $yearCurrent . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <div class="irs-demo">
                                            <span>จำนวนครั้งล้างคอขวด</span>
                                            <input class="js-range-slider" type="text" id="palmvolsilder" value="" />
                                            <input hidden type="text" id="s_min" name="s_min" 
                                            <?php if($fmin == -1) echo "value = '0'";
                                            else echo "value = '$fmin'"; ?> 
                                            />                                          
                                            <input hidden type="text" id="s_max" name="s_max" 
                                            <?php if($fmax == -1) echo "value = '0'";
                                            else echo "value = '$fmax'"; ?> 
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <span>จังหวัด</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_province" name="s_province" class="form-control ">
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
                                <div class="row  mb-2">
                                    <div class="col-12">
                                        <span>อำเภอ</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
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
                                <div class="row mb-2">
                                    <div class="col-11">

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>ชื่อเกษตรกร</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="text" class="form-control" id="s_name" name="s_name"
                                            <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>หมายเลขบัตรประชาชน</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="password" class="form-control input-setting" id="s_formalid"
                                            name="s_formalid"
                                            <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                        <i class="fa fa-eye-slash eye-setting" id="hide1"></i>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <button type="submit" id="btn_pass"
                                            class="btn btn-success btn-sm form-control">ค้นหา</button>
                                    </div>
                                </div>
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
            <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>; top: 25px; position: absolute; float:left;">
                การล้างคอขวดสวนปาล์มน้ำมัน</h6>
            <button type="button" id="add" style="float:right;" class="btn btn-success" data-toggle="tooltip">
                <i class="fas fa-plus"></i>เพิ่มการล้างคอขวด</button>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ชื่อเกษตรกร</th>
                            <th>ชื่อสวน</th>
                            <!-- <th>ชื่อแปลง</th> -->
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>วันที่ล่าสุด</th>
                            <th>จำนวนครั้ง</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ชื่อเกษตรกร</th>
                            <th>ชื่อสวน</th>
                            <!-- <th>ชื่อแปลง</th> -->
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>วันที่ล่าสุด</th>
                            <th>จำนวนครั้ง</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody id="body">
                        <label id="size" hidden size="<?php echo $DATA[0]['numrow']+1; ?>"></label>
                        <?php
                            for ($i = 1; $i <= $DATA[0]['numrow']; $i++) {
                                if($DATA[$i]['check_show'] == 1){
                                $time = $DATA[$i]['max_date'];
                                $time = strtotime($time);                                
                                // function thai_date_short($time){   // 19  ธ.ค. 2556
                                    global $dayTH,$monthTH_brev;   
                                    $thai_date_return = date("j",$time);   
                                    $strMonth = date("n",$time);   
                                    $strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
                                    $strMonthThai = $strMonthCut[$strMonth];
                                    $thai_date_return.=" ".$strMonthThai;   
                                    $thai_date_return.= " ".(date("Y",$time)+543);   
                                    
                                    $date =  $thai_date_return;   
                                // }
                                $lati = str_replace('.','-',$DATA[$i]["Latitude"]);
                                $longi = str_replace('.','-',$DATA[$i]["Longitude"]);
                        ?>
                        <tr class="<?php echo 'la'.$lati.'long'.$longi; ?> table-set" test="test<?php echo $i; ?>">
                            <td><?php echo $DATA[$i]["OwnerName"] ?></td>
                            <td><?php echo $DATA[$i]['Namefarm']; ?></td>
                            <!-- <td><?php echo $DATA[$i]["Namesubfarm"] ?></td> -->

                            <td class="text-right"><?php echo $DATA[$i]['AreaRai']; ?> ไร่
                                <?php echo $DATA[$i]['AreaNgan']; ?> งาน</td>
                            <td class="text-right"><?php echo $DATA[$i]['NumTree']; ?> ต้น</td>
                            <td class="text-center"><?php echo $date; ?></td>
                            <td class="text-right"><?php echo $DATA[$i]['time']; ?> ครั้ง</td>

                            <td style="text-align:center;">
                                <a href="./CutBranchDetail.php?farmID=<?php echo $DATA[$i]['dbID']?>">
                                    <button type="button" id='edit<?php echo $i; ?>'
                                        class="btn btn-info btn-sm btn-detail tt set-button" data-toggle="tooltip"
                                        title="รายละเอียด">
                                        <i class="fas fa-bars" ></i></button>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm btn-delete tt set-button"
                                    farmID="<?php echo $DATA[$i]['dbID']; ?>"
                                    farm="<?php echo $DATA[$i]['Namefarm']; ?>" date='<?php echo $date; ?>'
                                    data-toggle="tooltip" title="ลบ"><i class="far fa-trash-alt"></i></button>
                            </td>
                            <label class="click-map" hidden id="<?php echo $i; ?>"
                                namesubfarm="<?php echo $DATA[$i]["Namesubfarm"]; ?>"
                                namefarm="<?php echo $DATA[$i]["Namefarm"]; ?>"
                                dim_subfarm="<?php echo $DATA[$i]["dim_subfarm"]; ?>"
                                la="<?php echo $DATA[$i]["Latitude"]; ?>" long="<?php echo $DATA[$i]["Longitude"]; ?>"
                                check="<?php echo $DATA[$i]["check_show"]; ?>"
                                dist="<?php echo $DATA[$i]["Distrinct"]; ?>" pro="<?php echo $DATA[$i]["Province"]; ?>"
                                owner="<?php echo $DATA[$i]["OwnerName"]; ?>"></label>
                        </tr>
                        <?php
                                }
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
<?php  include_once("CutBranchModal.php"); ?>
<?php include_once("../../cropImage/cropImage.php");?>

<script src="CutBranch.js"></script>

<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th"
    async defer></script>