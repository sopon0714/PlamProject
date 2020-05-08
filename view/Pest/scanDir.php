<?php
$path = $_POST['path'];

function create_directory($path)
{
    $A_path = explode('/', $path);
    $path = "../..";
    foreach ($A_path as $val) {
        $path = $path . "/" . $val;
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            // echo "\n Insert " . $path . "\n";
        }
    }
}

function dirToArray($dir)
{
    $result = array();
    $cdir = scandir($dir, 1);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
            } else {
                $result[] = $value;
            }
        }
    }
    return $result;
}

if (!file_exists("../../" . $path))
    create_directory($path);
else {
    $data = dirToArray("../../" . $path);
    echo json_encode($data);
}
