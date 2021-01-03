<?php

require_once("../../dbConnect.php");
$myConDB = connectDB();
$result = $_POST["result"] ?? '';
if ($result == 'getDataDetail') {
    $FID = $_POST['FID'];
    $Type = $_POST['Type'];
    if ($Type  == "หลัก") {
        $Type = "ธาตุอาหารหลัก";
    } else if ($Type  == "รอง") {
        $Type = "ธาตุอาหารรอง";
    }
    $text = "";
    $sql = "SELECT `NID`,`Percent` FROM `log-fertilizercomposition` WHERE `FerID` = $FID ORDER BY `log-fertilizercomposition`.`NID`";
    $DATANUTR = selectData($sql);
    for ($j = 1; $j <= $DATANUTR[0]['numrow']; $j++) {
        $sql = "SELECT`dim-nutrient`.`Name`  FROM `log-nutrient` 
            INNER JOIN `dim-nutrient` ON `dim-nutrient`.`ID` = `log-nutrient`.`DIMnutrID`
            WHERE `dim-nutrient`.`dbID` = {$DATANUTR[$j]['NID']} AND `log-nutrient`.`Type` ='$Type' ORDER BY `log-nutrient`.`ID` DESC LIMIT 1";
        //echo $sql . "<br>";
        $DATADETAILNUTR = selectData($sql);
        if ($DATADETAILNUTR[0]['numrow'] == 1) {
            $text .= "  <tr>
                            <td >{$DATADETAILNUTR[1]['Name']}</td>
                            <td class=\"text-right\" >" . number_format($DATANUTR[$j]['Percent'], 2, '.', ',') . "</td>
                        </tr>";
        }
    }
    echo $text;
} else if ($result == 'delete') {
    $FID = $_POST['FID'];
    $sql = "UPDATE `log-fertilizer` SET `isDelete` = '1' WHERE `log-fertilizer`.`ID` = $FID";
    updateData($sql);
} else if ($result == 'updateInfo') {
    $sql = "SELECT * FROM `log-fertilizer`";
    print_r(json_encode(select($sql)));
}
