<?php
    // echo "hello";
    $time_start = microtime(true);
    ini_set('memory_limit', '-1');

set_time_limit(0);
require_once("../../dbConnect.php");
include_once("./../../query/query.php");


$myConDB = connectDB();
$sql = "SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))";
$myConDB->exec($sql);


$YEAR22= getYearAgriMap();
print_r($YEAR22);
echo "<br>";
$YEAR2 = array("2561","2562","2563");
$MONTH = array("มกราคม","กุมภา","มีนา","เมษา"); 
$MONTH_ARR = array("");
$DAY = array (1,2,3,4,5,6);
$DAY_ARR = array("");

$FARMER = array("วิเชียร","ชนะ");
    
$SET1_TITLE = array("Province"=>array(),"Distrinct"=>array(),"SubDistrinct"=>array(),"F_name"=>array(),"SF_name"=>array());
// $PROVINCE = array("1"=>array("AD1ID"=>"P1","Province"=>"กรุงเทพ"),"2"=>array("AD1ID"=>"P2","Province"=>"นครปฐม"));
// $DISTINCT =  array("1"=>array("AD1ID"=>"P1","AD2ID"=>"D1","Distrinct"=>"เขต จตุจักร"),"2"=>array("AD1ID"=>"P2","AD2ID"=>"D2","Distrinct"=>"กำแพงแสน"),"3"=>array("AD1ID"=>"P2","AD2ID"=>"D3","Distrinct"=>"เมืองนครปฐม"));
// $SUBDISTINCT = array("1"=>array("AD2ID"=>"D1","AD3ID"=>"SD1","SubDistrinct"=>"ลาดยาว"),"2"=>array("AD2ID"=>"D1","AD3ID"=>"SD2","SubDistrinct"=>"ทุ่งลูกนก"),"3"=>array("AD2ID"=>"D3","AD3ID"=>"SD3","SubDistrinct"=>"ห้วยจรเข้"));
$PROVINCE = array("กรุงเทพ","นครปฐม");
$DISTINCT = array("เขตจตุจักร-กทม","กำแพงแสน-นคร","เมืองนครปฐม-นคร");
$SUBDISTINCT = array("ลาดยาว-จตจ","ทุ่งลูกนก-กพส","ห้วยจรเข้-มนคร");

$FARM = array("สวน1","สวน2");
$SUBFARM = array("แปลง1สวน1","แปลง2สวน1","แปลง3สวน1","แปลง1สวน2");
    
$SET1_TITLE["Province"] = $PROVINCE;    
$SET1_TITLE["Distrinct"] = $DISTINCT;
$SET1_TITLE["SubDistrinct"] = $SUBDISTINCT;
$SET1_TITLE["F_name"] = $FARM;
$SET1_TITLE["SF_name"] = $SUBFARM;

// print_r($SET1_TITLE);
// echo "<br>";
// echo "<br>";
// echo "<br>";

$SET2_TITLE = array(""=>array(),"FM_name"=>array());
$SET2_TITLE["FM_name"] = $FARMER;

$SET3_ALL = array();

// $SET1_ALL = array();
// $SET2_ALL = array();
// echo "SET 1 <br>";
// foreach ($SET1_TITLE as $tt1 => $array1) {
//     $SET1_ALL = everyPossible($tt1,$array1);
//     print_r($SET1_ALL);
//     echo "<br>";
// }
// echo "SET 2 <br>";
// foreach ($SET2_TITLE as $tt2 => $array2) {
//     $SET2_ALL = everyPossible($tt2,$array2);
//     print_r($SET2_ALL);
//     echo "<br>";
// }
// echo "<br><br>";

$SET1_ALL = array();
$SET2_ALL = array();
$SET1 = array();
$SET2 = array();
$SET3 = array();
// foreach ($SET1_TITLE as $tt1 => $array1) {
//     $SET1_ALL = everyPossible($tt1,$array1);
//     foreach ($SET1_ALL as $in1){
//         $SET1 = $in1;
//         foreach ($SET2_TITLE as $tt2 => $array2) {
//             $SET2_ALL = everyPossible($tt2,$array2);
//             foreach ($SET2_ALL as $in2){
//                 $SET2 = $in2;
//                 echo "SET 1 = ";
//                 print_r($SET1);
//                 echo "<br> SET 2 = ";
//                 print_r($SET2);
//                 echo "<br>";

//             }
//         }
//         echo "---------------------------------<br>";
//     }
    
// }
// echo "<br>";

// echo "<br>";


// $SET3 = array();

// print_r($SET3);

$SET3[0] = "";
$SET3[1] = "";
$SET3[2] = "";
// print_r($SET3);
// echo "<br>";
array_push($SET3_ALL,$SET3); 
$SET3[0]="Year2";
for($y1=0;$y1<count($YEAR2);$y1++){
    for($y2=$y1;$y2<count($YEAR2);$y2++){
        $SET3[1] = $YEAR2[$y1];
        $SET3[2] = $YEAR2[$y2];
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

//SHOW ALL
// $SET3[0]="Year2";
// for($y1=0;$y1<count($YEAR2);$y1++){
//     for($y2=$y1;$y2<count($YEAR2);$y2++){
//         $SET3[1] = $YEAR2[$y1];
//         $SET3[2] = $YEAR2[$y2];
//         if($SET3[1] == $SET3[2]){
//             $SET3[3] = "Month";
//         }else{
//             $SET3[3] = "";
//         }
//             for($m1=0;$m1<count($MONTH);$m1++){
//                 for($m2=$m1;$m2<count($MONTH);$m2++){
//                         $SET3[4] = $MONTH[$m1];
//                         $SET3[5] = $MONTH[$m2];
//                         if($SET3[4] == $SET3[5]){
//                             $SET3[6] = "dd";
//                         }else{
//                             $SET3[6] = "";
//                         }
//                         for($d1=0;$d1<count($DAY);$d1++){
//                             for($d2=$d1;$d2<count($DAY);$d2++){
//                                 $SET3[7] = $DAY[$d1];
//                                 $SET3[8] = $DAY[$d2];
//                                 print_r($SET3);
//                                 echo "<br>";
//                             }
//                         }
//                 }
//             }
//     }
// }


echo "<br><br><br>";

foreach ($SET1_TITLE as $tt1 => $array1) {
    $SET1_ALL = everyPossible($tt1,$array1);
}
foreach ($SET2_TITLE as $tt2 => $array2) {
    $SET2_ALL = everyPossible($tt2,$array2);
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
function everyPossible($title,$ARR){
    $SET = array();
    $num_arr = 1;
    $num = 1;
    $SET[0] = "";

    for($a=0;$a<count($ARR);$a++){
        for($b=$a;$b<count($ARR);$b++){
            $arr = array();
            $num_arr=1;
            for($c=$a;$c<=$b;$c++){
                $arr[0] = $title;
                $arr[$num_arr++] = $ARR[$c];
                // echo $ARR[$c]." ";
            }
            $SET[$num++] = $arr;
            // echo "<br>";
        }
    }
    return $SET;
}



function convertToHoursMins($time, $format = '%d:%d') {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}
// echo convertToHoursMins(70);

$time_end = microtime(true);
$time = $time_end - $time_start; #เวลาเริ่มต้น – เวลาท้ายสุด

echo "เวลาที่ใช้ในการประมวลทั้งหมด $time วินาที\n";

?>