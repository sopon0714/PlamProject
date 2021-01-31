<?php
session_start();

$idUT = $_SESSION[md5('typeid')];
$CurrentMenu = "CutBranch";

include_once("../layout/LayoutHeader.php");
include_once("./../../query/query.php");
if (isset($_GET['farmID'])) {
    $farmID = $_GET['farmID'];
    $DATA = getActivityDetail($farmID, 1);
    var_dump($DATA);
    $name = explode(" ", $DATA[0]['OwnerName']);
    if ($DATA[0]['IconFarm'] == 'default.png') {
        $pathFarm = "../../icon/farm/0/defultFarm.png";
    } else {
        $pathFarm = "../../icon/farm/" . $DATA[0]['FMID'] . "/" . $DATA[0]['IconFarm'];
    }

    if ($DATA[0]['IconOwner'] == 'default.jpg') {
        if ($name[0] == 'นาย') {
            $pathname = "man.jpg";
        } else {
            $pathname = "woman.jpg";
        }
        $pathOwner = "../../icon/farmer/" . $pathname;
    } else {
        $pathOwner = "../../icon/farmer/" . $DATA[0]['UFID'] . "/" . $DATA[0]['IconOwner'];
    }
}

$head = "ล้างคอขวด";

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
                            <span class="link-active" style="color: #006664;">รายละเอียดการ<?php echo $head; ?></span>
                            <span style="float:right;">
                                <i class="fas fa-bookmark"></i>
                                <a class="link-path" href="#">หน้าแรก</a>
                                <span> > </span>
                                <a class="link-path" href="#"><?php echo $head; ?></a>
                                <span> > </span>
                                <a class="link-path link-active" style="color: #006664;" href="#">รายละเอียดการ<?php echo $head; ?></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <img class="img-radius img-profile" src="<?php echo $pathFarm; ?>" />
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <label id="fmid" hidden value="<?php echo $farmID; ?>"></label>
                        <span>ชื่อสวน : <?php echo $DATA[0]['Namefarm'] ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <img class="img-radius img-profile" src="<?php echo $pathOwner; ?>" />
                    </div>
                    <div class="row mt-3 justify-content-center">
                        <span>เกษตรกร : <?php echo $DATA[0]['OwnerName']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-12 col-12">
            <div class="card">
                <div class="card-header card-bg">
                    <div>
                        <span>การ<?php echo $head; ?>ปาล์มน้ำมันในระบบ</span>
                        <!-- <span style="float:right;" class="getSelectYear">ปี <?php echo $currentYear; ?></span> -->
                        <button type="button" id="add" style="float:right;" class="btn btn-success" data-toggle="modal" data-target="#modal-4"><i class="fas fa-plus"></i>
                            เพิ่มการ<?php echo $head; ?></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-data tableSearch" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ชื่อแปลง</th>
                                    <th>วันที่</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>

                            <tfoot>
                                <tr>
                                    <th>ชื่อแปลง</th>
                                    <th>วันที่</th>
                                    <th>จัดการ</th>
                                </tr>
                            </tfoot>
                            <tbody id="body">
                                <label id="size" hidden size="<?php echo sizeof($DATA); ?>"></label>
                                <?php
                                for ($i = 1; $i < sizeof($DATA); $i++) {
                                    $time = $DATA[$i]['Date'];
                                    $time = strtotime($time);
                                    // function thai_date_short($time){   // 19  ธ.ค. 2556
                                    global $dayTH, $monthTH_brev;
                                    $thai_date_return = date("j", $time);
                                    $strMonth = date("n", $time);
                                    $strMonthCut = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
                                    $strMonthThai = $strMonthCut[$strMonth];
                                    $thai_date_return .= " " . $strMonthThai;
                                    $thai_date_return .= " " . (date("Y", $time) + 543);

                                    $date =  $thai_date_return;
                                    // }
                                    $lati = str_replace('.', '-', $DATA[$i]["Latitude"]);
                                    $longi = str_replace('.', '-', $DATA[$i]["Longitude"]);
                                ?>
                                    <tr class="<?php echo 'la' . $lati . 'long' . $longi; ?> table-set" test="test<?php echo $i; ?>">
                                        <?php if ($DATA[$i]["EndT_sub"] != NULL) { ?>
                                            <td><?php echo $DATA[$i]["Namesubfarm"] ?></td>
                                        <?php } else { ?>
                                            <td><a href="./../view/OilPalmAreaList/OilPalmAreaListSubDetail.php?FSID=<?php echo $DATA[$i]["FSID"] ?>&FMID=<?php echo $DATA[$i]["FMID"] ?>">
                                                    <?php echo $DATA[$i]["Namesubfarm"] ?></a></td>
                                        <?php } ?>
                                        <td class="text-center"><?php echo $date; ?></td>

                                        <td style="text-align:center;">
                                            <button type="button" id='edit<?php echo $i; ?>' class="btn btn-warning btn-sm btn-detail tt set-button" data-toggle="tooltip" title="รายละเอียด" farm="<?php echo $DATA[0]['Namefarm']; ?>" subfarm="<?php echo $DATA[$i]['Namesubfarm']; ?>" date="<?php echo $date; ?>" o_farm="<?php echo $DATA[$i]['NameFarm_old']; ?>" modify="<?php echo $DATA[$i]['Modify']; ?>" o_subfarm="<?php echo $DATA[$i]['NamesubFarm_old']; ?>" pesttype_name="<?php echo $DATA[$i]['TypeTH']; ?>" pesttype="<?php echo $DATA[$i]['dbpestTID']; ?>" pestalias="<?php echo $DATA[$i]['PestAlias']; ?>" pest="<?php echo $DATA[$i]['DIMpestID']; ?>" note="<?php echo $DATA[$i]['Note']; ?>" lid="<?php echo $DATA[$i]['ID']; ?>">
                                                <i class="far fa-file"></i></button>
                                            <button type="button" class="btn btn-info btn-sm btn-photo tt set-button" lid="<?php echo $DATA[$i]['ID']; ?>" data-toggle="tooltip" title="รูปภาพการ<?php echo $head; ?>"><i class="far fa-images"></i></button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete tt set-button" fmid=<?php echo $farmID; ?> lid="<?php echo $DATA[$i]['ID']; ?>" subfarm="<?php echo $DATA[$i]['Namesubfarm']; ?>" date='<?php echo $date; ?>' data-toggle="tooltip" title="ลบ"><i class="far fa-trash-alt"></i></button>
                                        </td>
                                        <label class="click-map" hidden id="<?php echo $i; ?>" namesubfarm="<?php echo $DATA[$i]["Namesubfarm"]; ?>" dim_subfarm="<?php echo $DATA[$i]["dim_subfarm"]; ?>" la="<?php echo $DATA[$i]["Latitude"]; ?>" long="<?php echo $DATA[$i]["Longitude"]; ?>" check="<?php echo $DATA[$i]["check_show"]; ?>" dist="<?php echo $DATA[$i]["Distrinct"]; ?>" pro="<?php echo $DATA[$i]["Province"]; ?>" owner="<?php echo $DATA[$i]["OwnerName"]; ?>"></label>
                                    </tr>
                                <?php
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

<?php include_once("../layout/LayoutFooter.php"); ?>
<?php include_once($CurrentMenu . "DetailModal.php"); ?>
<?php include_once("../../cropImage/cropImage.php"); ?>

<script src="CutBranchDetail.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBMLhtSzox02ZCq2p9IIuihhMv5WS2isyo&callback=initMap&language=th" async defer></script>