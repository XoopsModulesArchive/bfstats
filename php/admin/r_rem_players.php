<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
@$todo = $_REQUEST["todo"];

if ($xoopsUser->isAdmin()) {
    if ($todo == "delete") {
        $id = $_REQUEST["id"];
        SQL_query("DELETE FROM selectbf_players where id = $id");
        SQL_query("DELETE FROM selectbf_playtimes where player_id = $id");
        SQL_query("DELETE FROM selectbf_attacks where player_id = $id");
        SQL_query("DELETE FROM selectbf_deaths where player_id = $id");
        SQL_query("DELETE FROM selectbf_drives where player_id = $id");
        SQL_query("DELETE FROM selectbf_heals where player_id = $id or healed_player_id = $id");
        SQL_query("DELETE FROM selectbf_kills where player_id = $id or victim_id = $id");
        SQL_query("DELETE FROM selectbf_kits where player_id = $id");
        SQL_query("DELETE FROM selectbf_playerstats where player_id = $id");
        SQL_query("DELETE FROM selectbf_repairs where player_id = $id or repair_player_id = $id");
        SQL_query("DELETE FROM selectbf_selfkills where player_id = $id");
        SQL_query("DELETE FROM selectbf_tks where player_id = $id");
        SQL_query("DELETE FROM selectbf_cache_ranking where player_id = $id");
        //msg("Player $id <b>deleted</b>!<br>");
        redirect_header("password.php", 1, 'Player $id <b>deleted</b>!<br>');//EDITED BY WIDOWMAKER
    }

    Header("Location: rem_players.php");
} else {
    Header("Location: login.php");
}
?>
