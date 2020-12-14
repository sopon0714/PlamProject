
<?php
    echo "test json file<br>";
    $namefile = "1-0-1-1---";
    $namefile = "./filedata/".$namefile.".json";
    echo $namefile."<br>";

    $myfile = file_get_contents($namefile);
    print_r($myfile);

?>