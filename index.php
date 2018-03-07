<head>
<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<?php
ini_set("display_errors", 0);
$filename = 'config.php';

if (file_exists($filename)) {
    //echo "The file $filename exists";
	echo"<meta http-equiv=\"refresh\" content=\"0; url=export_xp.php\">";
} else {
    //echo "The file $filename does not exist";
    echo '<font color="red"><b>ไฟล์การติดตั้งยังไม่ถูกสร้าง</b></font> <a href="preconfig.php">สร้างไฟล์การติดตั้ง.</a>';
	}


?>
