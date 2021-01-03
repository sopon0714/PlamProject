<?php
$time_start = microtime(true);
//สคริปต์ทั้งหมดทำงาน
ini_set('memory_limit', '-1');
set_time_limit(3000);
require_once("../../dbConnect.php");
include_once("./../../query/query.php");

$YEAR2= getYearAgriMap();
$MONTH = array(1,2,3,4,5,6,7,8,9,10,11,12);
$MONTH_ARR = array("");
$DAY = array (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$DAY_ARR = array("");

$FARMER = getFarmerAll_Chart();
    
$SET1_TITLE = array("Province"=>array(),"Distrinct"=>array(),"subDistrinct"=>array(),"F_name"=>array(),"SF_name"=>array());

$PROVINCE = getProvince();
$PROVINCE_ALL = array();
$DISTINCT = getDistrinct();
$DISTINCT_ALL = array();
$SUBDISTINCT = getSubDistrinct();
$SUBDISTINCT_ALL = array();

$FARM = getFarmAll();
$FARM_ALL = array();
$SUBFARM = getsubfarmAll();
$SUBFARM_ALL = array();

$SET2_TITLE = array("FM_name"=>array());


$SET1_ALL = array();
$SET1_ARR = array();
$SET2_ALL = array();
$SET3_ALL = array();
$SET1 = array();
$SET2 = array();
$SET3 = array();

// echo "<br><br><br>";
// print_r($SET1_TITLE);
// echo "<br>";
$PROVINCE_ALL["PROV"] = $PROVINCE;
$DISTINCT_ALL = eachID($DISTINCT,"AD1ID");
$SUBDISTINCT_ALL = eachID($SUBDISTINCT,"AD2ID");
$FARM_ALL = eachID($FARM,"dbsubDID");
$SUBFARM_ALL = eachID($SUBFARM,"f_dbID");

$SET1_TITLE["Province"] = $PROVINCE_ALL;    
$SET1_TITLE["Distrinct"] = $DISTINCT_ALL;
$SET1_TITLE["subDistrinct"] = $SUBDISTINCT_ALL;
$SET1_TITLE["F_name"] = $FARM_ALL;
$SET1_TITLE["SF_name"] = $SUBFARM_ALL;

// $FARMER_ALL["FARMER"] = $FARMER;
// $SET2_TITLE["FM_name"] = $FARMER;

foreach ($SET1_TITLE as $tt1 => $array1) {
    array_push($SET1_ARR,everyPossible1($tt1,$array1));
}
// print_r($SET1_ARR);
// echo "<br>";
foreach ($SET1_ARR as $set1_a) {
    foreach ($set1_a as $array1) {
        array_push($SET1_ALL,$array1);
    }
}
$SET2_ALL = everyPossible("FM_name",$FARMER);
// foreach ($SET2_TITLE as $tt2 => $array2) {
//     array_push($SET2_ALL,everyPossible($tt2,$array2));
// }   

// print_r($SET2_ALL);
// echo "<br>*****************************<br>";

$SET3[0] = "";
$SET3[1] = "";
$SET3[2] = "";
$SET3[3] = "";
$SET3[4] = "";
$SET3[5] = "";
$SET3[6] = "";
$SET3[7] = "";
$SET3[8] = "";
array_push($SET3_ALL,$SET3); 
$SET3[0]="Year2";
for($y1=1;$y1<count($YEAR2);$y1++){
    for($y2=$y1;$y2<count($YEAR2);$y2++){
        $SET3[1] = $YEAR2[$y1]["Year2"];
        $SET3[2] = $YEAR2[$y2]["Year2"];
        if($SET3[1] == $SET3[2]){
            $MONTH_FOR = $MONTH;
            $SET3[3] = "Month";
        }else{
            $MONTH_FOR = $MONTH_ARR;
            $SET3[3] = "";
        }
            for($m1=0;$m1<count($MONTH_FOR);$m1++){
                for($m2=$m1;$m2<count($MONTH_FOR);$m2++){
                        $SET3[4] = $MONTH_FOR[$m1];
                        $SET3[5] = $MONTH_FOR[$m2];
                        if($SET3[4] == $SET3[5] && $SET3[4] != ""){
                            $DAY_FOR = $DAY;
                            $SET3[6] = "dd";
                        }else{
                            $DAY_FOR = $DAY_ARR;
                            $SET3[6] = "";
                        }
                        for($d1=0;$d1<count($DAY_FOR);$d1++){
                            for($d2=$d1;$d2<count($DAY_FOR);$d2++){
                                $SET3[7] = $DAY_FOR[$d1];
                                $SET3[8] = $DAY_FOR[$d2];
                                // print_r($SET3);
                                // echo "<br>";
                                array_push($SET3_ALL,$SET3); 
                            }
                        }
                }
            }
    }
}

foreach ($SET1_ALL as $SET1){
    foreach ($SET2_ALL as $SET2){
        foreach($SET3_ALL as $SET3){
            echo "SET1 = ";
            print_r($SET1);
            echo "<br>SET2 = ";
            print_r($SET2);
            echo "<br>SET3 =";
            print_r($SET3);
            echo "<br>";
        }
        echo "********************************<br>";
    }
    echo "////////////////////////////////////////////<br>";
}

echo "--------------------------------------------------------<br>";
echo "<br>";

echo "<br>";
function everyPossible1($title,$ARR){
    $SET = array();
    $num_arr = 1;
    $num = 1;
    $SET[0] = "";
    foreach($ARR as $array){
        for($a=1;$a<count($array);$a++){
            for($b=$a;$b<count($array);$b++){
                $arr = array();
                $num_arr=1;
                for($c=$a;$c<=$b;$c++){
                    $arr[0] = $title;
                    $arr[$num_arr++] = $array[$c][$title];
                    // echo $array[$c]." ";
                }
                $SET[$num++] = $arr;
                // echo "<br>";
            }
        }
    }
    
    return $SET;
}
function everyPossible($title,$ARR){
    $SET = array();
    $num_arr = 1;
    $num = 1;
    $SET[0] = "";

    for($a=1;$a<count($ARR);$a++){
        for($b=$a;$b<count($ARR);$b++){
            $arr = array();
            $num_arr=1;
            for($c=$a;$c<=$b;$c++){
                $arr[0] = $title;
                $arr[$num_arr++] = $ARR[$c][$title];
                // echo $ARR[$c]." ";
            }
            $SET[$num++] = $arr;
            // echo "<br>";
        }
    }
    return $SET;
}
function eachID($ARR,$id){
    $ARR_ALL = array();
    for($i=1;$i<=$ARR[0]['numrow'];$i++){
        $ADID = $ARR[$i][$id]; 
        $ARR_ALL[$ADID] = array();
    }
    for($i=1;$i<=$ARR[0]['numrow'];$i++){
        $ADID = $ARR[$i][$id]; 
        array_push($ARR_ALL[$ADID],$ARR[$i]);
    }
    return $ARR_ALL;
}

$time_end = microtime(true);
$time = $time_end - $time_start; #เวลาเริ่มต้น – เวลาท้ายสุด

echo "เวลาที่ใช้ในการประมวลทั้งหมด $time วินาที\n";
?>