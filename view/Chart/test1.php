<?php
set_time_limit(1800);
require_once("../../dbConnect.php");
include_once("./../../query/query.php");
$SET1 = "";
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";
$SET1 = array("Province","กรุงเทพมหานคร","นครปฐม","กำแพงเพชร");
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";
$SET1 = array("Distrinct","กำแพงแสน","กงหรา");
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";
$SET1 = array("subDistrinct","พระบรมมหาราชวัง","วังบูรพาภิรมย์");
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";
$SET1 = array("F_name","บจ.ซีพีไอ อะโกรเทค(CPI)","FARM4");
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";
$SET1 = array("SF_name","แปลง1 ไฟโตตรอน","แปลง2 farm");
$SET1_ID = dataToID($SET1);
print_r($SET1_ID);
echo "<br>";

$SET2 = array("FM_name","นาย วิเชียร ธารสุวรรณ","นาย จรูญ ประดับการ");
$SET2_ID = dataToID($SET2);
print_r($SET2_ID);
echo "<br>";


function dataToID($ARR2){
    $array = array();
    $ARR1 = array();

    if($ARR2 != ''){
        if($ARR2[0] == 'Province'){
            $ARR1 = getProvince(); 
            $id = "AD1ID";
            $title = 1;
        }else if($ARR2[0] == 'Distrinct'){
            $ARR1 = getDistrinct(); 
            $id = "AD2ID";
            $title = 2;
        }else if($ARR2[0] == 'subDistrinct'){
            $ARR1 = getSubDistrinct();
            $id = "AD3ID";
            $title = 3;
        }else if($ARR2[0] == 'F_name'){
            $ARR1 = getFarmAll();
            $id = "dbID";
            $title = 4;
        }else if($ARR2[0] == 'SF_name'){
            $ARR1 = getSubfarmAll();
            $id = "sb_dbID";
            $title = 5;
        }else if($ARR2[0] == 'FM_name'){
            $ARR1 = getFarmerAll_Chart();
            $id = "dbID";
            $title = 1;
        }
        $array[0] = $title;
        for($i=1;$i<count($ARR2);$i++){
            for($j=1;$j<=$ARR1[0]['numrow'];$j++){
                if($ARR2[$i] == $ARR1[$j][$ARR2[0]]){
                    $array[$i] = $ARR1[$j][$id];
                    break;
                }
            }
        }
    }
    
    return $array;

}
?>