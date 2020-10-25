<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
@$todo = $_REQUEST["todo"];

if ($xoopsUser->isAdmin()) {
    if ($todo == "game") {
        $id = $_REQUEST["id"];
        deleteGame($id);
        //msg("Game $id <b>deleted</b>!<br>");
        redirect_header("rem_rounds.php", 1, 'Game $id <b>deleted</b>!<br>');//EDITED BY WIDOWMAKER
    } elseif ($todo == "round") {
        deleteRound($id);
        //msg("Round $id <b>deleted</b>!<br>");
        redirect_header("rem_rounds.php", 1, 'Round $id <b>deleted</b>!<br>');//EDITED BY WIDOWMAKER
    }

    if (isset($_REQUEST["gameid"])) {
        Header("Location: rem_rounds.php?id=" . $gameid);
    } else {
        Header("Location: rem_rounds.php");
    }
} else {
    Header("Location: login.php");
}
?>
