<?php
require_once("../../dbConnect.php");
require_once("../../set-log-login.php");
require_once("../../query/query.php");
session_start();
if (isset($_POST['action'])) {
    switch ($_POST['action']) {
        case "insert":
            $name = $_POST['name'];
            $alias = $_POST['alias'];
            $mainID = $_POST['mainID'];
            $main = $_POST['main'];
            $subID = $_POST['subID'];
            $sub = $_POST['sub'];
            $DIMDATE =  getDIMDate();
            $sql = "INSERT INTO `log-fertilizer` (`ID`, `isDelete`, `DIMdateID`, `Name`, `Alias`) 
            VALUES (NULL, '0', '{$DIMDATE[1]['ID']}', '$name', '$alias')";
            $IDFer = addinsertData($sql);
            $lenMain = count($mainID);
            $lenSub = count($subID);
            for ($i = 0; $i < $lenMain; $i++) {
                if ($main[$i] > 0) {
                    $sql = "INSERT INTO `log-fertilizercomposition` (`ID`, `FerID`, `NID`, `Percent`) 
                    VALUES (NULL, '$IDFer', '$mainID[$i]', '$main[$i]')";
                    addinsertData($sql);
                }
            }
            for ($i = 0; $i < $lenSub; $i++) {
                if ($sub[$i] > 0) {
                    $sql = "INSERT INTO `log-fertilizercomposition` (`ID`, `FerID`, `NID`, `Percent`) 
                    VALUES (NULL, '$IDFer', '$subID[$i]', '$sub[$i]')";
                    addinsertData($sql);
                }
            }
            header("location:./FertilizerList.php");
    }
}
