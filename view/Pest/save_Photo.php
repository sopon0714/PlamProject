<?php
$request = $_POST['request'];
$idCurrent = trim($_POST['idCurrent']);
$idPhoto = $_POST['idPhoto'];
$path = "picture/activities/pest/$idCurrent";

function getImg($img)
{
    if ($img != null) {
        $data = $img;
        $img_array = explode(';', $data);
        $img_array2 = explode(",", $img_array[1]);
        $data_pic = base64_decode($img_array2[1]);
        return $data_pic;
    } else return null;
}
function create_directory()
{
    $A_path = explode('/', $GLOBALS['path']);
    $GLOBALS['path'] = "../..";
    foreach ($A_path as $val) {
        $GLOBALS['path'] = $GLOBALS['path'] . "/" . $val;
        if (!file_exists($GLOBALS['path'])) {
            mkdir($GLOBALS['path'], 0777, true);
            // echo "\n Insert " . $path . "\n";
        }
    }
}
switch ($request) {
    case 'insert':
        create_directory();
        $base64 = $_POST['base64'];
        $pic = getImg($base64);
        file_put_contents($GLOBALS['path'] . "/" . substr($GLOBALS['idPhoto'], 0, 1) . "_" . time() . ".jpg", $pic);
        // echo $GLOBALS['request'] . " " . $GLOBALS['idCurrent'] . " " . $GLOBALS['idPhoto'] . " \n1\n";
        usleep(500000);
        break;
    case 'edit':
        $base64 = $_POST['base64'];
        if (substr($base64, 0, 10) == "data:image") {
            $sdir = scandir("../../" . $path);
            for ($i = 2; $i < count($sdir); $i++) {
                if ($sdir[$i] == $idPhoto . ".jpg") {
                    unlink("../../" . $path . "/" . $sdir[$i]);
                }
            }
            $pic = getImg($base64);
            file_put_contents("../../" . $GLOBALS['path'] . "/" . substr($GLOBALS['idPhoto'], 0, 1) . "_" . time() . ".jpg", $pic);
            usleep(500000);
        } else if ($base64 == '') {
            unlink("../../" . $path . "/" . $GLOBALS['idPhoto'] . ".jpg");
        }
        break;
}
