<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
@$item = $_REQUEST["item"];
@$mod = $_REQUEST["mod"];
$todo = $_REQUEST["todo"];
@$id = $_REQUEST["id"];

@session_start();

if ($xoopsUser->isAdmin()) {
    switch ($todo) {
        case "kit":
            addAssignment($item, $mod, "KIT");
            //msg("New Mod-Assignment for Kit <b>".$item."</b> saved<br>"); <<-ORIGINAL SCRIPT
            redirect_header("mod-assign.php", 1, 'New Mod-Assignment for Kit <b>' . $item . '</b> saved<br>');//EDITED BY WIDOWMAKER
            break;
        case "weapon":
            addAssignment($item, $mod, "WEAPON");
            //msg("New Mod-Assignment for Weapon <b>".$item."</b> saved<br>"); <<-ORIGINAL SCRIPT
            redirect_header("mod-assign.php", 1, 'New Mod-Assignment for Weapon <b>' . $item . '</b> saved');//EDITED BY WIDOWMAKER
            break;
        case "delete":
            deleteAssignment($id);
            //msg("Assignment <b>deleted</b><br>"); <<-ORIGINAL SCRIPT
            redirect_header("mod-assign.php", 1, 'Assignment <b>deleted</b><br>');//EDITED BY WIDOWMAKER
            break;
    }

    if (isset($_SESSION["AssignmentKIT"])) {
        unset($_SESSION["AssignmentKIT"]);
    }
    if (isset($_SESSION["AssignmentWEAPON"])) {
        unset($_SESSION["AssignmentWEAPON"]);
    }

    Header("Location: mod-assign.php");
} else {
    Header("Location: login.php");
}
?>
