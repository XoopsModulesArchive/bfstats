<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
$minrounds     = $_REQUEST["minrounds"];
$starnumber    = $_REQUEST["starnumber"];
$orderbycolumn = $_REQUEST["orderbycolumn"];

if ($xoopsUser->isAdmin()) {
    if (is_numeric($minrounds)) {
        setMinRounds($minrounds);
        //msg("<i>Minimum Rounds</i> value saved<br>");
        redirect_header("display.php", 1, '<i>Minimum Rounds</i> value saved<br>');//EDITED BY WIDOWMAKER
    } else {
        //error("Minimum Rounds-value is not a number!<br>");
        redirect_header("display.php", 1, 'Minimum Rounds-value is not a number!<br>');//EDITED BY WIDOWMAKER
    }

    if (is_numeric($starnumber)) {
        setStarNumber($starnumber);
        //msg("<i>Number of stars</i> value saved<br>");
        redirect_header("display.php", 1, '<i>Number of stars</i> value saved<br>');//EDITED BY WIDOWMAKER
    } else {
        //error("The value for <i>number of stars</i> is not a number!<br>");
        redirect_header("clear-text.php", 1, 'The value for <i>number of stars</i> is not a number!<br>');//EDITED BY WIDOWMAKER
    }

    setRankOrderByColumn($orderbycolumn);
    //msg("<i>Startup Order Column</i> saved<br>");
    redirect_header("clear-text.php", 1, '<i>Startup Order Column</i> saved<br>');//EDITED BY WIDOWMAKER
    Header("Location: display.php");
} else {
    Header("Location: login.php");
}
?>
