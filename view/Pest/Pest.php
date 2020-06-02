<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "Pest";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
$idformal = '';
$fullname = '';
$fyear = 0;
$ftype = 0;
$fpro = 0;
$fdist = 0;

$DATA = getPest($idformal, $fullname, $fpro, $fdist ,$fyear ,$ftype);
$PROVINCE = getProvince();
$DISTRINCT_PROVINCE = getDistrinctInProvince($fpro);
$PESTTYPE = getPestType();

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
        .upload-demo2  {
            width: 350px;
            height: 250px;
        }
        .img-gal {
            width: 150px;
            height: 100px;
            z-index: 5;
        }
    </style>

<div class="container">

    <div class="row">
        <div class="col-xl-12 col-12 mb-4">
            <div class="card">
                <div class="card-header card-bg">
                    <div class="row">
                        <div class="col-12">
                            <span class="link-active font-weight-bold" style="color:<?= $color ?>;">ตรวจพบศัตรูพืช</span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path link-active" href="#" style="color:<?= $color ?>;">ตรวจพบศัตรูพืช</a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <?php
        creatCard("card-color-one",   "จำนวนครั้งพบศัตรูพืช", getCountPestAlarm() . " ครั้ง", "waves");
        creatCard("card-color-two",   "จำนวนสวน",  getAreaLogFarm()[1]["NumFarm"]. " สวน " . getAreaLogFarm()[1]["NumSubFarm"] . " แปลง", "group");
        creatCard("card-color-three",   "พื้นที่ทั้งหมด", getAreaLogFarm()[1]["AreaRai"] . " ไร่ ".getAreaLogFarm()[1]["AreaNgan"] . " งาน", "dashboard");
        creatCard("card-color-four",   "จำนวนต้นไม้", getAreaLogFarm()[1]['NumTree'] . " ต้น", "format_size");
        ?>

    </div>

    <form action="Pest.php?isSearch=1" method="post">
        <div class="row">
            <div class="col-xl-12 col-12 mb-4">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header collapsed" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne" style="cursor:pointer; background-color: <?= $color ?>; color: white;">
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
                        ตำแหน่งแปลงการตรวจพบศัตรูพืช
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6 col-12">
                                <div id="map" style="width:auto;height:60vh;"></div>
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
                                <div class="row">
                                    <div class="col-12">
                                        <span>ชนิด</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <select id="s_type" name="s_type" class="form-control ">
                                            <option selected value=0>เลือกชนิด</option>
                                            <?php
                                            for ($i = 1; $i < sizeof($PESTTYPE); $i++) {
                                                if ($ftype == $PESTTYPE[$i]["PTID"])
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '" selected>' . $PESTTYPE[$i]["TypeTH"] . '</option>';
                                                else
                                                    echo '<option value="' . $PESTTYPE[$i]["PTID"] . '">' .$PESTTYPE[$i]["TypeTH"] . '</option>';
                                            }
                                            ?>
                                        </select>
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
                                        <input type="text" class="form-control" id="s_name" name="s_name" <?php if ($fullname != '') echo 'value="' . $fullname . '"'; ?>>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-11">
                                        <span>หมายเลขบัตรประชาชน</span>
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-12">
                                        <input type="password" class="form-control input-setting" id="s_formalid" name="s_formalid" <?php if ($idformal != '') echo 'value="' . $idformal . '"'; ?>>
                                        <i class="fa fa-eye-slash eye-setting" id="hide1"></i>
                                    </div>
                                </div>
                                <div class="row mb-2 padding">
                                    <div class="col-12">
                                        <button type="summit" id="btn_search" class="btn btn-success btn-sm form-control">ค้นหา</button>
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
            <h6 class="m-0 font-weight-bold" style="color:<?= $color ?>; top: 25px; position: absolute; float:left;">ศัตรูพืชสวนปาล์มน้ำมันในระบบ</h6>
            <button type="button" id="add" style="float:right;" class="btn btn-success" data-toggle="tooltip">
                <i class="fas fa-plus"></i>เพิ่มการตรวจพบศัตรูพืช</button>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ชื่อเกษตรกร</th>
                            <th>ชื่อสวน</th>
                            <th>ชื่อแปลง</th>
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>ชนิด</th>
                            <th>วันที่พบ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tfoot>
                        <tr>
                            <th>ชื่อเกษตรกร</th>
                            <th>ชื่อสวน</th>
                            <th>ชื่อแปลง</th>
                            <th>พื้นที่ปลูก</th>
                            <th>จำนวนต้น</th>
                            <th>ชนิด</th>
                            <th>วันที่พบ</th>
                            <th>จัดการ</th>
                        </tr>
                    </tfoot>
                    <tbody id="body">
                        <label id="size" hidden size ="<?php echo sizeof($DATA); ?>"></label>
                        <?php
                            for ($i = 1; $i < sizeof($DATA); $i++) {
                                $time = $DATA[$i]['Date'];
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
                        ?>
                                <tr class="<?php echo $i; ?>" test="test<?php echo $i; ?>">
                                    <td><?php echo $DATA[$i]["OwnerName"] ?></td>
                                    <td><?php echo $DATA[$i]['Namefarm']; ?></td>
                                    <td><?php echo $DATA[$i]["Namesubfarm"] ?></td>

                                    <td class="text-right"><?php echo $DATA[$i]['AreaRai']; ?> ไร่ <?php echo $DATA[$i]['AreaNgan']; ?> งาน</td>
                                    <td class="text-right"><?php echo $DATA[$i]['NumTree'];; ?> ต้น</td>
                                    <td class="text-left"><?php echo $DATA[$i]['TypeTH']; ?></td>
                                    <td class="text-center"><?php echo $date; ?></td>

                                    <td style="text-align:center;">
                                        <button type="button" id='edit<?php echo $i; ?>' class="btn btn-warning btn-sm btn-edit tt" data-toggle="tooltip" title="แก้ไข"
                                        date = "<?php echo $DATA[$i]['Date']; ?>" farm="<?php echo $DATA[$i]['DIMfarmID']; ?>" modify ="<?php echo $DATA[$i]['Modify']; ?>"
                                        subfarm = "<?php echo $DATA[$i]['DIMsubFID']; ?>" pesttype = "<?php echo $DATA[$i]['dbpestTID']; ?>"
                                        pest = "<?php echo $DATA[$i]['DIMpestID']; ?>" note = "<?php echo $DATA[$i]['Note']; ?>" lid = "<?php echo $DATA[$i]['ID']; ?>">
                                        <i class="fas fa-edit"></i></button>
                                        <button type="button" class="btn btn-success btn-sm btn-pest tt" dimpest = "<?php echo $DATA[$i]['dim_pest']; ?>" pest = "<?php echo $DATA[$i]['dbpestLID']; ?>"
                                        pesttype = "<?php echo $DATA[$i]['dbpestTID']; ?>" data-toggle="tooltip" title="ลักษณะ"><i class="fas fa-bars"></i></button>
                                        <button type="button" class="btn btn-info btn-sm btn-photo tt" lid="<?php echo $DATA[$i]['ID']; ?>"
                                        data-toggle="tooltip" title="รูปภาพศัตรูพืช"><i class="far fa-images"></i></button>
                                        <button type="button" class="btn btn-primary btn-sm btn-note tt" note = "<?php echo $DATA[$i]['Note']; ?>"
                                        data-toggle="tooltip" title="ข้อมูลสำคัญของศัตรูพืช"><i class="far fa-sticky-note"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete tt"
                                        onclick="delfunction('<?php echo $DATA[$i]['ID']; ?>','<?php echo $DATA[$i]['Namesubfarm']; ?>','<?php echo $DATA[$i]['NameFarm']; ?>')"
                                        data-toggle="tooltip" title="ลบ"><i class="far fa-trash-alt"></i></button>
                                    </td>
                                    <label class="click-map" hidden id="<?php echo $i; ?>" namesubfarm="<?php echo $DATA[$i]["Namesubfarm"]; ?>" AD3ID="" la="<?php echo $DATA[$i]["Latitude"]; ?>" 
                                    long="<?php echo $DATA[$i]["Longitude"]; ?>" ></label>
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
<?php  include_once("PestModal.php"); ?>
<?php include_once("../../cropImage/cropImage.php");?>

<script src="Pest.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>