<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
$debuglevel  = $_REQUEST["debuglvl"];
$titleprefix = $_REQUEST["titleprefix"];

if ($xoopsUser->isAdmin()) {
    changeDebugLevel($debuglevel);
    setTitlePrefix($titleprefix);
    //msg("Settings saved!");
    redirect_header("general.php", 1, 'Settings Saved!!');//EDITED BY WIDOWMAKER
    Header("Location: general.php");
} else {
    Header("Location: login.php");
}
?>
