<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

if ($xoopsUser->isAdmin()) {
    if (isset($_REQUEST["sure"]) && isset($_REQUEST["reallysure"])) {
        SQL_query("TRUNCATE `selectbf_cache_chartypeusage`");
        SQL_query("TRUNCATE `selectbf_cache_mapstats`");
        SQL_query("TRUNCATE `selectbf_cache_ranking");
        SQL_query("TRUNCATE `selectbf_cache_vehicletime`");
        SQL_query("TRUNCATE `selectbf_cache_weaponkills`");
        SQL_query("TRUNCATE `selectbf_attacks`");
        SQL_query("TRUNCATE `selectbf_deaths`");
        SQL_query("TRUNCATE `selectbf_drives`");
        SQL_query("TRUNCATE `selectbf_games`");
        SQL_query("TRUNCATE `selectbf_heals`");
        SQL_query("TRUNCATE `selectbf_kills`");
        SQL_query("TRUNCATE `selectbf_kits`");
        SQL_query("TRUNCATE `selectbf_players`");
        SQL_query("TRUNCATE `selectbf_playtimes`");
        SQL_query("TRUNCATE `selectbf_playerstats`");
        SQL_query("TRUNCATE `selectbf_repairs`");
        SQL_query("TRUNCATE `selectbf_rounds`");
        SQL_query("TRUNCATE `selectbf_selfkills`");
        SQL_query("TRUNCATE `selectbf_tks`");
        //msg("<i>Stats</i> reseted<br>");
        redirect_header("reset.php", 1, '<i>Stats</i> reset<br>');//EDITED BY WIDOWMAKER
    } else {
        //error("I were not <b>sure</b> and <b>really sure</b>!<br>");
        redirect_header("reset.php", 1, 'You were not 100% sure of this action.');//EDITED BY WIDOWMAKER

    }
    Header("Location: reset.php");
} else {
    Header("Location: login.php");
}
?>
