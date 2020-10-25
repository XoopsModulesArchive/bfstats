<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
$template = $_REQUEST["template"];

if ($xoopsUser->isAdmin()) {
    changeActiveTemplate($template);
    //msg("Template changed!");
    redirect_header("templates.php", 1, 'Template changed!');//EDITED BY WIDOWMAKER
    Header("Location: templates.php");
} else {
    Header("Location: login.php");
}
?>
