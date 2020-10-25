<?

include "../../../mainfile.php";
require XOOPS_ROOT_PATH . "/header.php";

global $xoopsConfig;
$xoopsDB;
$xoopsTheme;

require_once "include/vLib/vlibTemplate.php";
require_once "include/sql.php";
require_once "include/func.php";

//start the processtime-timer
$starttime = timer();

//add the specified template's config
$TEMPLATE_DIR = getActiveTemplate();
if (checkTemplateConsistency($TEMPLATE_DIR, "config.php")) {
    require_once "templates/$TEMPLATE_DIR/config.php";
} else {
    die(Template_error("The config.php is missing!"));
}

//now start setting the variables for the Template
if (!checkTemplateConsistency($TEMPLATE_DIR, "character.html")) {
    die(Template_error("This page is not templated yet, plz create a 'character.html' for the '$TEMPLATE_DIR'-Template!"));
}
$tmpl = new vlibTemplate("templates/$TEMPLATE_DIR/character.html");

//set basic Template-Variables
$tmpl->setVar("TITLE", getActiveTitlePrefix() . " - Character-Types");
$tmpl->setVar("CSS", "templates/$TEMPLATE_DIR/include/$TMPL_CFG_CSS");
$tmpl->setVar("IMAGES_DIR", "templates/$TEMPLATE_DIR/images/");
$tmpl->setVar("ADMINMODE_LINK", "admin/index.php");
$tmpl->setLoop("NAVBAR", getNavBar());

$contextbar = [];
$contextbar = addContextItem($contextbar, getActiveTitlePrefix() . "-statistics");
$contextbar = addLinkedContextItem($contextbar, "index.php", "Ranking");
$contextbar = addContextItem($contextbar, "Character-Types");
$tmpl->setLoop("CONTEXTBAR", $contextbar);

$tmpl->setLoop("charactertypes", getCharacterTypes());
$tmpl->setLoop("repairs", getRepairs());
$tmpl->setLoop("heals", getHeals());

//now finish the processtime timer
$totaltime = timer() - $starttime;
$tmpl->setVar("PROCESSTIME", sprintf("%01.2f seconds", $totaltime));

@$tmpl->pparse();
?>
<?php
require XOOPS_ROOT_PATH . "/footer.php";
?>



