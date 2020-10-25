<?php

include "../../../../mainfile.php";
require_once XOOPS_ROOT_PATH . "/class/xoopsmodule.php";
require XOOPS_ROOT_PATH . "/include/cp_functions.php";

if (file_exists("/language/" . $xoopsConfig['language'] . "/admin/mainfile.php")) {
    include "/language/" . $xoopsConfig['language'] . "/admin/mainfile.php";
} else {
    include "/language/english/admin/mainfile.php";
}

?>
