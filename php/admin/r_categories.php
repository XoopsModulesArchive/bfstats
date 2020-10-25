<?

include "admin_header.php";
include "../../../../mainfile.php";
require "../include/sql.php";
require "admin_func.php";

//read the needed vars
@$category_name = $_REQUEST["cat_name"];
@$collect_data = $_REQUEST["col_data"];
@$datasource_name = $_REQUEST["datas_name"];
$todo = $_REQUEST["todo"];
@$id = $_REQUEST["id"];
@$member = $_REQUEST["member"];

@session_start();

if ($xoopsUser->isAdmin()) {
    switch ($todo) {
        case "new_cat":
            if ($category_name != "" && $datasource_name != "") {
                createCategory($category_name, $collect_data, $datasource_name, "WEAPON");
                //msg("New Category <b>".$category_name."</b> added<br>");
                redirect_header("categories.php", 1, 'New Category <b>' . $category_name . '</b> added<br>');//EDITED BY WIDOWMAKER
            } else {
                //error("Please enter a value for every text-field!");
                redirect_header("categories.php", 1, 'Please enter a value for every text-field');//EDITED BY WIDOWMAKER

            }
            break;
        case "new_member":
            addMember($id, $member);
            //msg("New Member <b>".$member."</b> added<br>");
            redirect_header("categories.php", 1, 'New Member <b>' . $member . '</b> added<br>');//EDITED BY WIDOWMAKER
            break;
        case "delete_member":
            deleteMember($id, $member);
            //msg("Member <b>".$member."</b> deleted from Category<br>");
            redirect_header("categories.php", 1, 'Member <b>' . $member . '</b> deleted from Category<br>');//EDITED BY WIDOWMAKER
            break;
        case "delete_category":
            deleteCategory($id);
            //msg("Category <b>deleted</b>!");
            redirect_header("categories.php", 1, 'Category <b>deleted</b>!');//EDITED BY WIDOWMAKER
            break;
        case "change_collect":
            changeCollectData($collect_data, $id);
            //msg("<b>Collect-Data</b>-value changed!<br>");
            redirect_header("categories.php", 1, '<b>Collect-Data</b>-value changed!<br>');//EDITED BY WIDOWMAKER
            break;
    }

    Header("Location: categories.php");
} else {
    Header("Location: login.php");
}
?>
