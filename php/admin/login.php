<?

include "admin_header.php";
include "../../mainfile.php";
require_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
require_once XOOPS_ROOT_PATH . "/class/module.errorhandler.php";
global $xoopsDB;
xoops_cp_header();
OpenTable();

require_once "../include/vLib/vlibTemplate.php";
require "../include/sql.php";
require "admin_func.php";
require_once "../templates/default/config.php";

if (!$xoopsUser->isAdmin()) {
    Header("Location: index.php");
} else {
    //start the processtime-timer
    $starttime = timer();

    //now start setting the variables for the Template
    $tmpl = new vlibTemplate("../templates/default/admin/login.html");
    //evaluate Messages and Errors!
    require_once "messages.php";

    //set basic Template-Variables
    $tmpl->setVar("TITLE", "select(bf) - Admin Panel");
    //$tmpl->setVar("CSS","../templates/default/include/$TMPL_CFG_CSS");
    $tmpl->setVar("IMAGES_DIR", "../templates/default/images/");

    $tmpl->setVar("formaction", "r_login.php");
    $tmpl->setVar("param_password", "password");

    //now finish the processtime timer
    $totaltime = timer() - $starttime;
    $tmpl->setVar("PROCESSTIME", sprintf("%01.2f seconds", $totaltime));

    @$tmpl->pparse();
}
CloseTable();
xoops_cp_footer();
?>
