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
    $tmpl = new vlibTemplate("../templates/default/admin/categories.html");
    //evaluate Messages and Errors!
    require_once "messages.php";

    //set basic Template-Variables
    $tmpl->setVar("TITLE", "select(bf) - Admin Panel");
    //$tmpl->setVar("CSS","../templates/default/include/$TMPL_CFG_CSS");
    $tmpl->setVar("IMAGES_DIR", "../templates/default/images/");

    $tmpl->setVar("form_action", "r_categories.php");
    $tmpl->setVar("param_todo", "todo");
    $tmpl->setVar("todo_value_change_collect", "change_collect");
    $tmpl->setVar("todo_value_new_category", "new_cat");
    $tmpl->setVar("todo_value_new_member", "new_member");

    $tmpl->setVar("param_category_name", "cat_name");
    $tmpl->setVar("param_collectdata", "col_data");
    $tmpl->setVar("param_datasource_name", "datas_name");
    $tmpl->setVar("param_member", "member");
    $tmpl->setVar("param_category_id", "id");

    $categories = getCategories();
    $categories = addUnCategorized($categories);

    $tmpl->setLoop("categories", $categories);

    //now finish the processtime timer
    $totaltime = timer() - $starttime;
    $tmpl->setVar("PROCESSTIME", sprintf("%01.2f seconds", $totaltime));

    @$tmpl->pparse();
}
CloseTable();
xoops_cp_footer();
?>
