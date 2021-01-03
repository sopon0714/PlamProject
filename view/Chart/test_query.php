<?php
require_once("../../dbConnect.php");
include_once("./../../query/query.php");
$YEAR2= getYearAgriMap();
$MONTH = array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
$MONTH_ARR = array("");
$DAY = array (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$DAY_ARR = array("");

$SET3_ALL = array();
$SET3 = array();

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
                                print_r($SET3);
                                echo "<br>";
                                array_push($SET3_ALL,$SET3); 
                            }
                        }
                }
            }
    }
}

?>