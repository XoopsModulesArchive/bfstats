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
    $tmpl = new vlibTemplate("../templates/default/admin/display.html");
    //evaluate Messages and Errors!
    require_once "messages.php";

    //set basic Template-Variables
    $tmpl->setVar("TITLE", "select(bf) - Admin Panel");
    //$tmpl->setVar("CSS","../templates/default/include/$TMPL_CFG_CSS");
    $tmpl->setVar("IMAGES_DIR", "../templates/default/images/");

    $tmpl->setVar("form_action", "r_display.php");

    $tmpl->setVar("minrounds", getMinRounds());
    $tmpl->setVar("param_minrounds", "minrounds");

    $tmpl->setVar("starnumber", getStarNumber());
    $tmpl->setVar("param_starnumber", "starnumber");

    $orderbycolumn = getRankOrderbyColumn();
    $tmpl->setVar("orderbycolumn", $orderbycolumn);

    $orderbycolumns = [];
    /*if($orderbycolumn == "points") array_push($orderbycolumns,array("name"=>"points","selected"=>true));
    else array_push($orderbycolumns,array("name"=>"points","selected"=>false));*/
    if ($orderbycolumn == "score") {
        array_push($orderbycolumns, ["name" => "score", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "score", "selected" => false]);
    }
    if ($orderbycolumn == "kills") {
        array_push($orderbycolumns, ["name" => "kills", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "kills", "selected" => false]);
    }
    if ($orderbycolumn == "deaths") {
        array_push($orderbycolumns, ["name" => "deaths", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "deaths", "selected" => false]);
    }
    if ($orderbycolumn == "kdrate") {
        array_push($orderbycolumns, ["name" => "kdrate", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "kdrate", "selected" => false]);
    }
    if ($orderbycolumn == "first") {
        array_push($orderbycolumns, ["name" => "first", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "first", "selected" => false]);
    }
    if ($orderbycolumn == "second") {
        array_push($orderbycolumns, ["name" => "second", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "second", "selected" => false]);
    }
    if ($orderbycolumn == "third") {
        array_push($orderbycolumns, ["name" => "third", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "third", "selected" => false]);
    }
    if ($orderbycolumn == "tks") {
        array_push($orderbycolumns, ["name" => "tks", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "tks", "selected" => false]);
    }
    if ($orderbycolumn == "attacks") {
        array_push($orderbycolumns, ["name" => "attacks", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "attacks", "selected" => false]);
    }
    if ($orderbycolumn == "captures") {
        array_push($orderbycolumns, ["name" => "captures", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "captures", "selected" => false]);
    }
    if ($orderbycolumn == "objectives") {
        array_push($orderbycolumns, ["name" => "objectives", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "objectives", "selected" => false]);
    }
    if ($orderbycolumn == "rounds") {
        array_push($orderbycolumns, ["name" => "rounds", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "rounds", "selected" => false]);
    }
    if ($orderbycolumn == "heals") {
        array_push($orderbycolumns, ["name" => "heals", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "heals", "selected" => false]);
    }
    if ($orderbycolumn == "repairs") {
        array_push($orderbycolumns, ["name" => "repairs", "selected" => true]);
    } else {
        array_push($orderbycolumns, ["name" => "repairs", "selected" => false]);
    }

    $tmpl->setLoop("orderbycolumns", $orderbycolumns);
    $tmpl->setVar("param_orderbycolumn", "orderbycolumn");

    //now finish the processtime timer
    $totaltime = timer() - $starttime;
    $tmpl->setVar("PROCESSTIME", sprintf("%01.2f seconds", $totaltime));

    @$tmpl->pparse();
}
CloseTable();
xoops_cp_footer();
?>
