<?

function checkAdminPsw($psw)
{
    $Ergebnis = SQL_oneRowQuery("SELECT value from selectbf_admin where name='ADMIN_PSW'");
    if ($Ergebnis["value"] == md5($psw)) {
        return true;
    } else {
        return false;
    }
}

function deleteGame($id)
{
    //fetch all rounds that happened during that game
    $res = SQL_query("SELECT id FROM selectbf_rounds WHERE game_id=$id");

    while (false !== ($Ergebnis = SQL_fetchArray($res))) {
        deleteRound($Ergebnis["id"]);
    }
}

function deleteRound($id)
{
    //first check if this is the last round of the game to not leave any lonely games in the DB
    $Ergebnis = SQL_oneRowQuery("SELECT game_id from selectbf_rounds where id=$id");
    $gameid   = $Ergebnis["game_id"];

    $Ergebnis = SQL_oneRowQuery("SELECT count(*) count from selectbf_rounds where game_id=$gameid");
    $count    = $Ergebnis["count"];

    //terminate lonely games from DB
    if ($count == "1") {
        SQL_query("DELETE FROM selectbf_games WHERE id=$gameid");
    }

    //delete depending events
    SQL_query("DELETE FROM selectbf_attacks WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_deaths WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_drives WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_heals WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_kills WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_kits WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_playerstats WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_repairs WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_selfkills WHERE round_id=$id");
    SQL_query("DELETE FROM selectbf_tks WHERE round_id=$id");

    //then delete the round
    SQL_query("DELETE FROM selectbf_rounds WHERE id=$id");
}

function changePassword($str)
{
    SQL_query("UPDATE selectbf_admin SET value='" . md5($str) . "' where name='ADMIN_PSW'");
}

function addClearedText($uncleared, $cleared, $type)
{
    $uncleared = addslashes($uncleared);
    $cleared   = addslashes($cleared);
    SQL_query("INSERT INTO selectbf_cleartext (original,custom,type,inserttime) VALUES ('$uncleared','$cleared','$type',now())");
}

function deleteClearText($id)
{
    SQL_query("DELETE FROM selectbf_cleartext where id=$id");
}

function addMember($id, $member)
{
    SQL_query("INSERT INTO selectbf_categorymember (member,category) VALUES ('$member',$id)");
}

function deleteCategory($id)
{
    SQL_query("DELETE FROM selectbf_category where id = $id");
    SQL_query("DELETE FROM selectbf_categorymember where category = $id");
}

function changeCollectData($collect_data, $id)
{
    SQL_query("UPDATE selectbf_category SET collect_data=$collect_data where id=$id");
}

function deleteMember($id, $member)
{
    SQL_query("DELETE FROM selectbf_categorymember WHERE member='$member' AND category=$id");
}

function addUnCategorized($categories)
{
    $weapons = [];
    $res     = SQL_query("SELECT DISTINCT weapon FROM selectbf_kills ORDER BY weapon ASC");
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($weapons, $cols);
    }

    for ($i = 0; $i < count($categories); $i++) {
        $members = $categories[$i]["members"];

        $uncategorized = [];

        for ($j = 0; $j < count($weapons); $j++) {
            $found = false;
            for ($k = 0; $k < count($members) && $found === false; $k++) {
                if ($weapons[$j]["weapon"] == $members[$k]["member"]) {
                    $found = true;
                }
            }
            if ($found === false) {
                array_push($uncategorized, ["weapon" => $weapons[$j]["weapon"]]);
            }
        }

        $categories[$i]["uncategorized"] = $uncategorized;
    }

    return $categories;
}

function getCategories()
{
    $res = SQL_query("SELECT id,name,collect_data,datasource_name FROM selectbf_category WHERE type='WEAPON' ORDER BY name ASC");

    $categories = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $id   = $cols["id"];
        $name = $cols["name"];

        $collect_data = [];
        if ($cols["collect_data"] == "1") {
            array_push($collect_data, ["option" => "yes", "selected" => true, "value" => "1"]);
            array_push($collect_data, ["option" => "no", "selected" => false, "value" => "0"]);
        } else {
            array_push($collect_data, ["option" => "yes", "selected" => false, "value" => "1"]);
            array_push($collect_data, ["option" => "no", "selected" => true, "value" => "0"]);
        }
        $datasource = $cols["datasource_name"];
        $deletelink = "r_categories.php?todo=delete_category&id=" . $id;

        $member    = [];
        $resultset = SQL_query("select member from selectbf_categorymember where category = $id");
        $i         = 0;
        while (false !== ($columns = SQL_fetchArray($resultset))) {
            $columns["deletelink"] = "r_categories.php?todo=delete_member&member=" . $columns["member"] . "&id=" . $id;
            array_push($member, $columns);
            $i++;
        }
        if ($i == 0) {
            $member = false;
        }

        $category = ["id" => $id, "name" => $name, "collect_data" => $collect_data, "datasource_name" => $datasource, "deletelink" => $deletelink, "members" => $member];

        array_push($categories, $category);
    }

    return $categories;
}

function createCategory($name, $collectdata, $datasourcename, $type)
{
    $name           = addslashes($name);
    $datasourcename = addslashes($datasourcename);
    if ($collectdata != "1" && $collectdata != "0") {
        $collectdata = "0";
    }
    SQL_query("INSERT INTO selectbf_category (name,collect_data,datasource_name,type,inserttime) VALUES ('$name',$collectdata,'$datasourcename','$type',now())");
}

function getMods()
{
    $res  = SQL_query("SELECT DISTINCT modid MOD FROM selectbf_games ORDER BY mod ASC");
    $mods = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($mods, $cols);
    }

    return $mods;
}

function addAssignment($item, $mod, $type)
{
    $item = addslashes($item);
    $mod  = addslashes($mod);
    SQL_query("INSERT INTO selectbf_modassignment (item,mod,type,inserttime) VALUES ('$item','$mod','$type',now())");
}

function deleteAssignment($id)
{
    SQL_query("DELETE FROM selectbf_modassignment where id=$id");
}

function getUnAssignedWeapons()
{
    $res           = SQL_query("SELECT DISTINCT weapon FROM selectbf_kills ORDER BY weapon ASC");
    $total_weapons = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_weapons, ["weapon" => $cols["weapon"]]);
    }

    $res             = SQL_query("SELECT item weapon FROM selectbf_modassignment WHERE type='WEAPON' ORDER BY weapon ASC");
    $cleared_weapons = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_weapons, ["weapon" => $cols["weapon"]]);
    }

    $weapons = [];
    for ($i = 0; $i < count($total_weapons); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_weapons) && $found !== true; $j++) {
            if ($total_weapons[$i]["weapon"] == $cleared_weapons[$j]["weapon"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($weapons, ["weapon" => $total_weapons[$i]["weapon"], "cleartext" => clearUpText($total_weapons[$i]["weapon"], "WEAPON")]);
        }
    }

    return $weapons;
}

function getUnAssignedKits()
{
    $res        = SQL_query("SELECT DISTINCT kit FROM selectbf_kits ORDER BY kit ASC");
    $total_kits = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_kits, ["kit" => $cols["kit"]]);
    }

    $res          = SQL_query("SELECT item kit FROM selectbf_modassignment WHERE type='KIT' ORDER BY kit ASC");
    $cleared_kits = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_kits, ["kit" => $cols["kit"]]);
    }

    $kits = [];
    for ($i = 0; $i < count($total_kits); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_kits) && $found !== true; $j++) {
            if ($total_kits[$i]["kit"] == $cleared_kits[$j]["kit"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($kits, ["kit" => $total_kits[$i]["kit"], "cleartext" => clearUpText($total_kits[$i]["kit"], "KIT")]);
        }
    }

    return $kits;
}

function clearUpText($text, $type)
{
    @session_start();

    if (isset($_SESSION["ClearText" . $type])) {
        $lookup = $_SESSION["ClearText" . $type];

        if (isset($lookup[$text])) {
            return $lookup[$text];
        } else {
            return $text;
        }
    } else {
        $res    = SQL_query("select original, custom from selectbf_cleartext where type='$type' order by original ");
        $lookup = [];
        while (false !== ($cols = SQL_fetchArray($res))) {
            $lookup[$cols["original"]] = $cols["custom"];
        }
        $_SESSION["ClearText" . $type] = $lookup;

        if (isset($lookup[$text])) {
            return $lookup[$text];
        } else {
            return $text;
        }
    }
}

function getAssignments($type)
{
    $res = SQL_query("select id,item, mod from selectbf_modassignment where type='$type'");

    $assignments = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["item"]       = clearUpText($cols["item"], $type);
        $cols["deletelink"] = "r_mod-assign.php?todo=delete&id=" . $cols["id"];
        array_push($assignments, $cols);
    }

    return $assignments;
}

function getClearedGameModes()
{
    $res               = SQL_query("select id, original, custom from selectbf_cleartext where type='GAME-MODE' order by original ");
    $cleared_gamemodes = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["deletelink"] = "r_clear-text.php?todo=delete&id=" . $cols["id"];
        array_push($cleared_gamemodes, $cols);
    }

    return $cleared_gamemodes;
}

function getUnClearedGameModes()
{
    $res             = SQL_query("SELECT DISTINCT game_mode gamemode FROM selectbf_games ORDER BY gamemode ASC");
    $total_gamemodes = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_gamemodes, ["gamemode" => $cols["gamemode"]]);
    }

    $res               = SQL_query("SELECT original gamemode FROM selectbf_cleartext WHERE type='GAME-MODE' ORDER BY gamemode ASC");
    $cleared_gamemodes = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_gamemodes, ["gamemode" => $cols["gamemode"]]);
    }

    $gamemodes = [];
    for ($i = 0; $i < count($total_gamemodes); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_gamemodes) && $found !== true; $j++) {
            if ($total_gamemodes[$i]["gamemode"] == $cleared_gamemodes[$j]["gamemode"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($gamemodes, ["gamemode" => $total_gamemodes[$i]["gamemode"]]);
        }
    }

    return $gamemodes;
}

function getClearedWeapons()
{
    $res             = SQL_query("select id, original, custom from selectbf_cleartext where type='WEAPON' order by original ");
    $cleared_weapons = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["deletelink"] = "r_clear-text.php?todo=delete&id=" . $cols["id"];
        array_push($cleared_weapons, $cols);
    }

    return $cleared_weapons;
}

function getUnClearedWeapons()
{
    $res           = SQL_query("SELECT DISTINCT weapon FROM selectbf_kills ORDER BY weapon ASC");
    $total_weapons = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_weapons, ["weapon" => $cols["weapon"]]);
    }

    $res             = SQL_query("SELECT original weapon FROM selectbf_cleartext WHERE type='WEAPON' ORDER BY weapon ASC");
    $cleared_weapons = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_weapons, ["weapon" => $cols["weapon"]]);
    }

    $weapons = [];
    for ($i = 0; $i < count($total_weapons); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_weapons) && $found !== true; $j++) {
            if ($total_weapons[$i]["weapon"] == $cleared_weapons[$j]["weapon"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($weapons, ["weapon" => $total_weapons[$i]["weapon"]]);
        }
    }

    return $weapons;
}

function getClearedVehicles()
{
    $res              = SQL_query("select id, original, custom from selectbf_cleartext where type='VEHICLE' order by original ");
    $cleared_vehicles = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["deletelink"] = "r_clear-text.php?todo=delete&id=" . $cols["id"];
        array_push($cleared_vehicles, $cols);
    }

    return $cleared_vehicles;
}

function getUnClearedVehicles()
{
    $res            = SQL_query("SELECT DISTINCT vehicle FROM selectbf_drives ORDER BY vehicle ASC");
    $total_vehicles = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_vehicles, ["vehicle" => $cols["vehicle"]]);
    }

    $res              = SQL_query("SELECT original vehicle FROM selectbf_cleartext WHERE type='VEHICLE' ORDER BY vehicle ASC");
    $cleared_vehicles = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_vehicles, ["vehicle" => $cols["vehicle"]]);
    }

    $vehicles = [];
    for ($i = 0; $i < count($total_vehicles); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_vehicles) && $found !== true; $j++) {
            if ($total_vehicles[$i]["vehicle"] == $cleared_vehicles[$j]["vehicle"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($vehicles, ["vehicle" => $total_vehicles[$i]["vehicle"]]);
        }
    }

    return $vehicles;
}

function getClearedMaps()
{
    $res          = SQL_query("select id, original, custom from selectbf_cleartext where type='MAP' order by original ");
    $cleared_maps = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["deletelink"] = "r_clear-text.php?todo=delete&id=" . $cols["id"];
        array_push($cleared_maps, $cols);
    }

    return $cleared_maps;
}

function getUnClearedMaps()
{
    $res        = SQL_query("SELECT DISTINCT map FROM selectbf_games ORDER BY map ASC");
    $total_maps = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_maps, ["map" => $cols["map"]]);
    }

    $res          = SQL_query("SELECT original map FROM selectbf_cleartext WHERE type='MAP' ORDER BY map ASC");
    $cleared_maps = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_maps, ["map" => $cols["map"]]);
    }

    $maps = [];
    for ($i = 0; $i < count($total_maps); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_maps) && $found !== true; $j++) {
            if ($total_maps[$i]["map"] == $cleared_maps[$j]["map"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($maps, ["map" => $total_maps[$i]["map"]]);
        }
    }

    return $maps;
}

function getClearedKits()
{
    $res          = SQL_query("select id, original, custom from selectbf_cleartext where type='KIT' order by original ");
    $cleared_kits = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["deletelink"] = "r_clear-text.php?todo=delete&id=" . $cols["id"];
        array_push($cleared_kits, $cols);
    }

    return $cleared_kits;
}

function getUnClearedKits()
{
    $res        = SQL_query("SELECT DISTINCT kit FROM selectbf_kits ORDER BY kit ASC");
    $total_kits = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($total_kits, ["kit" => $cols["kit"]]);
    }

    $res          = SQL_query("SELECT original kit FROM selectbf_cleartext WHERE type='KIT' ORDER BY kit ASC");
    $cleared_kits = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($cleared_kits, ["kit" => $cols["kit"]]);
    }

    $kits = [];
    for ($i = 0; $i < count($total_kits); $i++) {
        $found = false;
        for ($j = 0; $j < count($cleared_kits) && $found !== true; $j++) {
            if ($total_kits[$i]["kit"] == $cleared_kits[$j]["kit"]) {
                $found = true;
            }
        }

        if ($found !== true) {
            array_push($kits, ["kit" => $total_kits[$i]["kit"]]);
        }
    }

    return $kits;
}

function registerAdminLogin($psw)
{
    $session_psw = $psw;
    session_register("session_psw");
    $_SESSION["session_psw"] = $session_psw;
}

function msg($str)
{
    @session_start();
    if (isset($_SESSION["msg"])) {
        $_SESSION["msg"] = $_SESSION["msg"] . $str;
    } else {
        $_SESSION["msg"] = $str;
    }
}

function error($str)
{
    @session_start();
    if (isset($_SESSION["error"])) {
        $_SESSION["error"] = $_SESSION["error"] . $str;
    } else {
        $_SESSION["error"] = $str;
    }
}

function getValueForParameter($str)
{
    $Ergebnis = SQL_oneRowQuery("select value from selectbf_params where name='$str'");
    return $Ergebnis["value"];
}

function setValueForParameter($value, $parameter)
{
    SQL_query("update selectbf_params SET value='$value' WHERE name='$parameter'");
}

function getTimeForParameter($str)
{
    $Ergebnis = SQL_oneRowQuery("select inserttime from selectbf_admin where name='$str'");
    return $Ergebnis["inserttime"];
}

function getRankOrderByColumn()
{
    return getValueForParameter("RANK-ORDERBY");
}

function setRankOrderByColumn($str)
{
    setValueForParameter($str, "RANK-ORDERBY");
}

function getStarNumber()
{
    return getValueForParameter("STAR-NUMBER");
}

function setStarNumber($str)
{
    setValueForParameter($str, "STAR-NUMBER");
}

function getMinRounds()
{
    return getValueForParameter("MIN-ROUNDS");
}

function setMinRounds($str)
{
    setValueForParameter($str, "MIN-ROUNDS");
}

function getTitlePrefix()
{
    return getValueForParameter("TITLE-PREFIX");
}

function setTitlePrefix($str)
{
    setValueForParameter($str, "TITLE-PREFIX");
}

function getParameterInfo($str)
{
    $Ergebnis = SQL_oneRowQuery("select name, value, inserttime from selectbf_admin where name='$str'");
    return $Ergebnis;
}

function printCheckedIf($value, $const)
{
    if ($value == $const) {
        echo "checked";
    }
}

function printSelectedIf($value, $const)
{
    if ($value == $const) {
        echo "selected";
    }
}

function isAdmin()
{
    @session_start();

    $session_psw = "";
    if (isset($_SESSION["session_psw"])) {
        $session_psw = $_SESSION["session_psw"];
    }

    if ($session_psw != "") {
        return true;
    } else {
        return false;
    }
}

function logoutAdmin()
{
    @session_start();
    session_destroy();
}

function timer()
{
    [$low, $high] = split(" ", microtime());
    $t = $high + $low;
    return $t;
}

function changeActiveTemplate($str)
{
    SQL_query("UPDATE selectbf_params set value='$str' WHERE name='TEMPLATE'");
}

function getActiveTemplate()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='TEMPLATE'");
    return $cols["value"];
}

function getDebugLevel()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='DEBUG-LEVEL'");
    return $cols["value"];
}

function changeDebugLevel($str)
{
    SQL_query("UPDATE selectbf_params set value='$str' WHERE name='DEBUG-LEVEL'");
}

?>
