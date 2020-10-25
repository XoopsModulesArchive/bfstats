<?

include "admin_header.php";
include "../../../../mainfile.php";
xoops_cp_header();
OpenTable();
require_once "../include/vLib/vlibTemplate.php";
require "../include/sql.php";
require "admin_func.php";
require_once "../templates/default/config.php";

if (!$xoopsUser->isAdmin()) {
    Header("Location: login.php");
} else {
    //start the processtime-timer
    $starttime = timer();

    //now start setting the variables for the Template
    $tmpl = new vlibTemplate("../templates/default/admin/rem_players.html");

    //set basic Template-Variables
    $tmpl->setVar("TITLE", "select(bf) - Admin Panel");
    //$tmpl->setVar("CSS","../templates/default/include/$TMPL_CFG_CSS");
    $tmpl->setVar("IMAGES_DIR", "../templates/default/images/");

    $tmpl->setVar("form_action", "rem_players.php");

    $tmpl->setVar("param_search_name", "search_name");

    if (isset($_REQUEST["search_name"])) {
        $searchresults = [];
        $playername    = $_REQUEST["search_name"];
        $playername    = $GLOBALS['xoopsDB']->escape($playername);

        $found = false;
        $res   = SQL_query("select id, name, CONCAT(TIME_FORMAT(inserttime,'%H:%i:%S '),DATE_FORMAT(inserttime,'%d|%m|%Y')) time from selectbf_players WHERE name LIKE '%$playername%' ORDER BY name ASC");
        while (false !== ($cols = SQL_fetchArray($res))) {
            $found              = true;
            $cols["deletelink"] = "r_rem_players.php?todo=delete&id=" . $cols["id"];
            array_push($searchresults, $cols);
        }

        if ($found) {
            $tmpl->setLoop("searchresults", $searchresults);
        } else {
            msg("There was no Player matching your search!");
        }
    }

    //evaluate Messages and Errors!
    require_once "messages.php";

    //now finish the processtime timer
    $totaltime = timer() - $starttime;
    $tmpl->setVar("PROCESSTIME", sprintf("%01.2f seconds", $totaltime));

    @$tmpl->pparse();
}
CloseTable();
xoops_cp_footer();
?>
