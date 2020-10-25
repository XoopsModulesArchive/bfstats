<?php

//for random picture generation
$start = (int)mktime(date("h"), date("i"), date("s"), date("d"), date("m"), date("Y"));
// mt_srand($start);

@session_start();

//if Debug-Messages should be displayed or not
$DEBUG = getActiveDebugLevel();
if ($DEBUG) {
    echo "<b><u>DEBUG</b>-Informations:</u><br>";
}

function getNavBar()
{
    $navbar = [];
    array_push($navbar, ["link" => "index.php", "name" => "Ranking"]);
    array_push($navbar, ["link" => "character.php", "name" => "Character-Types"]);
    array_push($navbar, ["link" => "weapon.php", "name" => "Weapons"]);
    array_push($navbar, ["link" => "vehicle.php", "name" => "Vehicles"]);
    array_push($navbar, ["link" => "maps.php", "name" => "Maps"]);
    array_push($navbar, ["link" => "usage.php", "name" => "Server-Usage"]);
    return $navbar;
}

function addLinkedContextItem($array, $link, $text)
{
    array_push($array, ["link" => $link, "name" => $text]);
    return $array;
}

function addContextItem($array, $text)
{
    array_push($array, ["link" => false, "name" => $text]);
    return $array;
}

function getMapImage($str)
{
    global $TMPL_CFG_MAP_IMG, $TEMPLATE_DIR;

    if (isset($TMPL_CFG_MAP_IMG[$str])) {
        return "templates/$TEMPLATE_DIR/images/" . $TMPL_CFG_MAP_IMG[$str];
    } else {
        return "templates/$TEMPLATE_DIR/images/blank.gif";
    }
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

function getModFor($text, $type)
{
    @session_start();

    if (isset($_SESSION["Assignment" . $type])) {
        $lookup = $_SESSION["Assignment" . $type];

        if (isset($lookup[$text])) {
            return $lookup[$text];
        } else {
            return null;
        }
    } else {
        $res    = SQL_query("select item, mod from selectbf_modassignment where type='$type' order by item ");
        $lookup = [];
        while (false !== ($cols = SQL_fetchArray($res))) {
            $lookup[$cols["item"]] = $cols["mod"];
        }
        $_SESSION["Assignment" . $type] = $lookup;

        if (isset($lookup[$text])) {
            return $lookup[$text];
        } else {
            return null;
        }
    }
}

function createWhereClause($arraymember, $colname)
{
    $str = "( $colname = ";

    for ($i = 0; $i < count($arraymember); $i++) {
        $member = $arraymember[$i]["member"];
        if ($i == 0) {
            $str = $str . " '$member'";
        } else {
            $str = "$str OR $colname = '$member'";
        }
    }

    $str = "$str )";

    return $str;
}

function getMapTKs($map)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.name,p.id, count(*) count from selectbf_tks k, selectbf_players p, selectbf_rounds r, selectbf_games g where k.player_id = p.id and k.round_id = r.id and r.game_id = g.id and g.map = '$map' group by p.name order by count DESC LIMIT 0,15");
    $max = getMaxForField($res, "count");

    $maptks = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols['playerdetaillink'] = "player.php?id=" . $cols['id'];
        $cols["playerimg"]        = randomPlayerImg();
        array_push($maptks, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapTKs</i></b> took $totaltime secs<br>");
    }

    return $maptks;
}

function getMapDrives($map)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select k.vehicle, sum(drivetime) drivetime from selectbf_drives k, selectbf_rounds r, selectbf_games g where k.round_id = r.id and r.game_id = g.id and g.map = '$map' group by k.vehicle order by drivetime DESC LIMIT 0,15");
    $max = getMaxForField($res, "drivetime");

    $mapdrives = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['vehicle'] = clearUpText($cols['vehicle'], "VEHICLE");
        $cols['bar']     = statsbar($cols['drivetime'], $max);
        $cols['time']    = sec2time($cols['drivetime']);
        array_push($mapdrives, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapDrives</i></b> took $totaltime secs<br>");
    }

    return $mapdrives;
}

function getMapDeaths($map)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.name,p.id, count(*) count from selectbf_deaths k, selectbf_players p, selectbf_rounds r, selectbf_games g where k.player_id = p.id and k.round_id = r.id and r.game_id = g.id and g.map = '$map' group by p.name order by count DESC LIMIT 0,15");
    $max = getMaxForField($res, "count");

    $mapdeaths = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['playerdetaillink'] = "player.php?id=" . $cols['id'];
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        array_push($mapdeaths, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapDeaths</i></b> took $totaltime secs<br>");
    }

    return $mapdeaths;
}

function getMapAttacks($map)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.name,p.id, count(*) count from selectbf_attacks k, selectbf_players p, selectbf_rounds r, selectbf_games g where k.player_id = p.id and k.round_id = r.id and r.game_id = g.id and g.map = '$map' group by p.name order by count DESC LIMIT 0,15");
    $max = getMaxForField($res, "count");

    $mapattacks = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['playerdetaillink'] = "player.php?id=" . $cols['id'];
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        array_push($mapattacks, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapAttacks</i></b> took $totaltime secs<br>");
    }

    return $mapattacks;
}

function getMapKills($map)
{
    global $DEBUG;
    $starttime = timer();

    $sql = "select p.name,p.id, count(*) count from selectbf_kills k, selectbf_players p, selectbf_rounds r, selectbf_games g where k.player_id = p.id and k.round_id = r.id and r.game_id = g.id and g.map = '$map' group by p.name order by count DESC LIMIT 0,15";
    $res = SQL_query($sql);
    $max = getMaxForField($res, "count");

    $mapkills = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['playerdetaillink'] = "player.php?id=" . $cols['id'];
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        array_push($mapkills, $cols);
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapKills</i></b> took $totaltime secs<br>");
    }

    return $mapkills;
}

function getMapStats()
{
    global $DEBUG, $TMPL_CFG_BAR_TEAMS, $TEMPLATE_DIR;
    $starttime = timer();

    $MAXWIDTH = $TMPL_CFG_BAR_TEAMS['maxwidth'];
    $HEIGHT   = $TMPL_CFG_BAR_TEAMS['height'];
    $left     = $TMPL_CFG_BAR_TEAMS['axis']['left'];
    $axis     = $TMPL_CFG_BAR_TEAMS['axis']['middle'];
    $allies   = $TMPL_CFG_BAR_TEAMS['allies']['middle'];
    $right    = $TMPL_CFG_BAR_TEAMS['axis']['right'];

    $mapstats = [];

    $res = SQL_query(
        "SELECT map, wins_team1, wins_team2,win_team1_tickets_team1 win_team1_end_tickets_team1,win_team1_tickets_team2 win_team1_end_tickets_team2,win_team2_tickets_team1 win_team2_end_tickets_team1,win_team2_tickets_team2 win_team2_end_tickets_team2,score_team1,score_team2,kills_team1,kills_team2,deaths_team1,deaths_team2,attacks_team1,attacks_team2,captures_team1,captures_team2 FROM selectbf_cache_mapstats ORDER BY map ASC"
    );
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["mapdetaillink"] = "map.php?map=" . urlencode($cols["map"]);
        $cols["screen"]        = getMapImage($cols["map"]);
        $cols["map"]           = clearUpText($cols["map"], "MAP");

        $sum = $cols["wins_team1"] + $cols["wins_team2"];

        if ($sum == 0) {
            $pixelAxis   = $MAXWIDTH / 2;
            $pixelAllies = $MAXWIDTH / 2;
        } else {
            $pixelAxis   = ($cols["wins_team1"] / $sum) * $MAXWIDTH;
            $pixelAllies = ($cols["wins_team2"] / $sum) * $MAXWIDTH;
        }

        $img = "<img src=templates/$TEMPLATE_DIR/images/$left>";
        $img = $img . "<img src=templates/$TEMPLATE_DIR/images/$axis width=\"$pixelAxis\" height=\"$HEIGHT\">";
        $img = $img . "<img src=templates/$TEMPLATE_DIR/images/$allies width=\"$pixelAllies\" height=\"$HEIGHT\">";
        $img = $img . "<img src=templates/$TEMPLATE_DIR/images/$right>";

        $cols["bar"] = $img;

        array_push($mapstats, $cols);
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapStats</i></b> took $totaltime secs<br>");
    }

    return $mapstats;
}

function getVehicleUsage()
{
    global $DEBUG, $TMPL_CFG_BAR, $TEMPLATE_DIR;
    $starttime = timer();

    $res = SQL_query("select vehicle, time, percentage_time, times_used, percentage_usage from selectbf_cache_vehicletime order by time DESC LIMIT 0,25");
    $max = getMaxForField($res, "time");

    $vehusage = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $vehicle = $cols["vehicle"];
        $res2    = SQL_query("select kills, percentage from selectbf_cache_weaponkills where weapon='$vehicle'");
        if ($cols2 = SQL_fetchArray($res2)) {
            $cols["kills"]            = $cols2["kills"];
            $cols["percentage_kills"] = $cols2["percentage"];
        } else {
            $cols["kills"]            = 0;
            $cols["percentage_kills"] = 0;
        }
        $cols['vehicle'] = clearUpText($cols['vehicle'], "VEHICLE");
        $cols['bar']     = statsbar($cols['time'], $max);
        $cols['time']    = sec2time($cols['time']);

        $cols['percentage_time']  = sprintf("%01.2f", $cols['percentage_time']);
        $cols['percentage_usage'] = sprintf("%01.2f", $cols['percentage_usage']);
        $cols['percentage_kills'] = sprintf("%01.2f", $cols['percentage_kills']);

        array_push($vehusage, $cols);
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getVehicleUsage</i></b> took $totaltime secs<br>");
    }

    return $vehusage;
}

function getDataForWeaponCategory($categoryid)
{
    global $DEBUG;
    $starttime = timer();

    $res     = SQL_query("select member from selectbf_categorymember where category = $categoryid");
    $members = [];
    $content = false;
    while (false !== ($cols = SQL_fetchArray($res))) {
        array_push($members, $cols);
        $content = true;
    }

    $data = [];
    if ($content) {
        $whereclause = createWhereClause($members, "k.weapon");
        $sql         = "select p.name,p.id,count(*) count from selectbf_kills k, selectbf_players p where k.player_id = p.id and $whereclause group by p.name,p.id order by count DESC LIMIT 0,15";
        $res         = SQL_query($sql);
        $max         = getMaxForField($res, "count");

        while (false !== ($cols = SQL_fetchArray($res))) {
            $cols['bar']              = statsbar($cols['count'], $max);
            $cols["playerimg"]        = randomPlayerImg();
            $id                       = $cols['id'];
            $cols["playerdetaillink"] = "player.php?id=$id";
            array_push($data, $cols);
        }
    } else {
        $data = null;
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getDataForWeaponCategory</i></b> took $totaltime secs<br>");
    }

    return $data;
}

function getActiveMinRoundsValue()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='MIN-ROUNDS'");
    return $cols['value'];
}

function getActiveTemplate()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='TEMPLATE'");
    return $cols['value'];
}

function checkTemplateConsistency($template, $check)
{
    $dir = opendir("templates/$template/");

    $found = false;
    while (($file = readdir($dir)) && !$found) {
        if ($file == $check) {
            $found = true;
        }
    }
    return $found;
}

function Template_error($msg)
{
    ?>
    <html>
    <head>
        <title>select(bf) Error</title>
        <link rel="stylesheet" href="templates/default/include/style.css" type="text/css">
    </head>
    <body>
    <center>
        <table class=navbar width=450>
            <tr class=axis>
                <th><img src="templates/default/images/icon_error.gif" align=absmiddle hspace=2> Template Error</th>
            </tr>
            <tr>
                <td class=admin>
                    <b>There is a problem with the configured Template.</b><br>
                    Please access the <a href="admin/index.php">Admin Mode</a> and change back to a working one.<br>
                    <b>OR</b> rework the template to meet the specifications.<p>
                        <u><b>Problem:</b></u>
                        <i><? echo $msg; ?></i><br>
                    <p>
                </td>
            </tr>
        </table>
    </center>
    </html>
    <?
}

function getActiveDebugLevel()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='DEBUG-LEVEL'");
    return $cols['value'];
}

function getActiveTitlePrefix()
{
    $cols = SQL_oneRowQuery("SELECT value FROM selectbf_params WHERE name='TITLE-PREFIX'");
    return $cols['value'];
}

function randomPlayerImg()
{
    global $TMPL_CFG_PLAYER_IMG, $TEMPLATE_DIR;

    $i = (int)mt_rand(0, sizeof($TMPL_CFG_PLAYER_IMG) - 1);

    return "templates/$TEMPLATE_DIR/images/$TMPL_CFG_PLAYER_IMG[$i]";
}

function getMaxForField($ResultSet, $Fieldname)
{
    $erg = 0;
    while (false !== ($Ergebnisse = SQL_fetchArray($ResultSet))) {
        if ($erg < $Ergebnisse[$Fieldname]) {
            $erg = $Ergebnisse[$Fieldname];
        }
    }
    SQL_resetResult($ResultSet);
    return $erg;
}

function getSumForField($ResultSet, $Fieldname)
{
    $erg = 0;
    while (false !== ($Ergebnisse = SQL_fetchArray($ResultSet))) {
        $erg += $Ergebnisse[$Fieldname];
    }
    SQL_resetResult($ResultSet);
    return $erg;
}

function pixelValue($value, $max, $maxpixel)
{
    if ($max == 0) {
        return 0;
    } else {
        return ($value * $maxpixel) / $max;
    }
}

function statsbar($value, $max)
{
    global $TMPL_CFG_BAR, $TEMPLATE_DIR;

    $width  = pixelValue($value, $max, $TMPL_CFG_BAR['maxwidth']);
    $left   = $TMPL_CFG_BAR['left'];
    $middle = $TMPL_CFG_BAR['middle'];
    $right  = $TMPL_CFG_BAR['right'];
    $height = $TMPL_CFG_BAR['height'];
    return "<img src=templates/$TEMPLATE_DIR/images/$left><img src=templates/$TEMPLATE_DIR/images/$middle width=\"$width\" height=\"$height\"><img src=templates/$TEMPLATE_DIR/images/$right>";
}

function getModSymbol($str, $type)
{
    global $TEMPLATE_DIR, $TMPL_CFG_MOD_IMG;

    $mod = getModFor($str, $type);

    if ($mod === null) {
        $img = "templates/$TEMPLATE_DIR/images/blank.gif";
    } else {
        if (isset($TMPL_CFG_MOD_IMG[$mod])) {
            $img = "templates/$TEMPLATE_DIR/images/" . $TMPL_CFG_MOD_IMG[$mod];
        } else {
            $img = "templates/$TEMPLATE_DIR/images/blank.gif";
        }
    }

    return $img;
}

function getModSymbolForName($str)
{
    global $TMPL_CFG_MOD_IMG, $TEMPLATE_DIR;

    $img = "templates/$TEMPLATE_DIR/images/blank.gif";

    if (isset($TMPL_CFG_MOD_IMG[$str])) {
        $img = "templates/$TEMPLATE_DIR/images/" . $TMPL_CFG_MOD_IMG[$str];
    }

    return $img;
}

function getRankingOrderByColumn()
{
    $cols = SQL_oneRowQuery("SELECT value from selectbf_params WHERE name='RANK-ORDERBY'");
    return $cols["value"];
}

function sec2time($str)
{
    $min = (int)($str / 60);

    $sec = $str - ($min * 60);

    $h   = (int)($min / 60);
    $min = $min - ($h * 60);

    $d = (int)($h / 24);
    $h = $h - ($d * 24);

    if ($min == 0 && $h == 0 && $d == 0) {
        return sprintf("%01.2fsec", $sec);
    } elseif ($h == 0 && $d == 0) {
        return sprintf("%dmin %01.2fsec", $min, $sec);
    } elseif ($d == 0) {
        return sprintf("%dh %dmin %01.2fsec", $h, $min, $sec);
    } else {
        return sprintf("%dd %dh %dmin %01.2fsec", $d, $h, $min, $sec);
    }
}

function timer()
{
    [$low, $high] = split(" ", microtime());
    $t = $high + $low;
    return $t;
}

function getMaxStarNumber()
{
    $Erg = SQL_oneRowQuery("select value from selectbf_params where name='STAR-NUMBER'");
    return $Erg["value"];
}

function getTopScorerId()
{
    $Erg = SQL_oneRowQuery("select ps.player_id id, sum(score) score from selectbf_playerstats ps group by ps.player_id order by score DESC LIMIT 0,1");
    return $Erg["id"];
}

function getTopPointerId()
{
    $Erg = SQL_oneRowQuery("select ps.player_id id, ((sum(kills)/sum(deaths))+(sum(attacks)*2/count(*))-(sum(tks)/count(*))+(sum(captures)*10/count(*)))*100 points from selectbf_playerstats ps group by ps.player_id order by points DESC LIMIT 0,1");
    return $Erg["id"];
}

function getTopMedicId()
{
    $Erg = SQL_oneRowQuery("select p.id,p.name, sum(amount) amount, sum(healtime) healtime from selectbf_heals h, selectbf_players p where player_id!=healed_player_id and h.player_id = p.id group by player_id order by amount DESC,healtime DESC LIMIT 0,1");
    return $Erg["id"];
}

function getTopEngineerId()
{
    $Erg = SQL_oneRowQuery("select p.id,p.name, sum(amount) amount, sum(repairtime) repairtime from selectbf_repairs r, selectbf_players p where player_id!=repair_player_id and r.player_id = p.id group by player_id order by amount DESC,repairtime DESC LIMIT 0,1");
    return $Erg["id"];
}

function getAwards($player_id, $first, $second, $third, $toprepair, $topheal, $topscore, $starnumber)
{
    global $TMPL_CFG_BRONZE_STAR_IMG, $TMPL_CFG_SILVER_STAR_IMG, $TMPL_CFG_GOLD_STAR_IMG, $TMPL_CFG_TOP_HEAL_IMG, $TMPL_CFG_TOP_POINT_IMG, $TMPL_CFG_TOP_REPAIR_IMG, $TMPL_CFG_TOP_SCORE_IMG, $TEMPLATE_DIR;

    $awards = [];

    $awards_withoutstars = "";
    $awards_withstars    = "";

    if ($first > $starnumber) {
        $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_GOLD_STAR_IMG\" align=absmiddle alt=\"Rank 1: $first Times\">x$first";
    } else {
        for ($i = 0; $i < $first; $i++) {
            $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_GOLD_STAR_IMG\" align=absmiddle alt=\"Rank 1: $first Times\">";
        }
    }
    if ($second > $starnumber) {
        $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_SILVER_STAR_IMG\" align=absmiddle alt=\"Rank 2: $second Times\">x$second";
    } else {
        for ($i = 0; $i < $second; $i++) {
            $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_SILVER_STAR_IMG\" align=absmiddle alt=\"Rank 2: $second Times\">";
        }
    }
    if ($third > $starnumber) {
        $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_BRONZE_STAR_IMG\" align=absmiddle alt=\"Rank 3: $third Times\">x$third";
    } else {
        for ($i = 0; $i < $third; $i++) {
            $awards_withstars = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_BRONZE_STAR_IMG\" align=absmiddle alt=\"Rank 3: $third Times\">";
        }
    }

    if ($topheal == $player_id) {
        $awards_withstars    = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_HEAL_IMG\" align=absmiddle alt=\"TOP-Medic\">";
        $awards_withoutstars = $awards_withoutstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_HEAL_IMG\" align=absmiddle alt=\"TOP-Medic\">";
    }
    if ($toprepair == $player_id) {
        $awards_withstars    = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_REPAIR_IMG\" align=absmiddle alt=\"TOP-Engineer\">";
        $awards_withoutstars = $awards_withoutstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_REPAIR_IMG\" align=absmiddle alt=\"TOP-Engineer\">";
    }
    if ($topscore == $player_id) {
        $awards_withstars    = $awards_withstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_SCORE_IMG\" align=absmiddle alt=\"TOP-Scorer\">";
        $awards_withoutstars = $awards_withoutstars . "<img src=\"templates/$TEMPLATE_DIR/images/$TMPL_CFG_TOP_SCORE_IMG\" align=absmiddle alt=\"TOP-Scorer\">";
    }

    $awards["awards_withstars"]    = $awards_withstars;
    $awards["awards_withoutstars"] = $awards_withoutstars;

    return $awards;
}

function getRanking($orderby, $direction, $start)
{
    global $DEBUG;
    $starttime = timer();

    $minrounds = getActiveMinRoundsValue();
    $res       = SQL_query(
        "select playername name,player_id id, score, tks, kills, deaths, captures, attacks, objectives, heals, selfheals, repairs, otherrepairs, rounds_played rounds, kdrate, first, second, third, playtime, score_per_minute from selectbf_cache_ranking having rounds > $minrounds order by $orderby $direction LIMIT $start,50"
    );

    $toprepair  = getTopEngineerId();
    $topheal    = getTopMedicId();
    $toppoint   = getTopPointerId();
    $topscore   = getTopScorerId();
    $starnumber = getMaxStarNumber();

    $ranking = [];
    $i       = $start + 1;
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols["rank"]                = $i;
        $cols["playerimg"]           = randomPlayerImg();
        $id                          = $cols['id'];
        $cols["playerdetaillink"]    = "player.php?id=$id";
        $awards                      = getAwards($id, $cols["first"], $cols["second"], $cols["third"], $toprepair, $topheal, $topscore, $starnumber);
        $cols["awards"]              = $awards["awards_withstars"];
        $cols["awards_withoutstars"] = $awards["awards_withoutstars"];
        array_push($ranking, $cols);
        $i++;
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getRanking</i></b> took $totaltime secs<br>");
    }

    return $ranking;
}

function getServerUsage()
{
    global $DEBUG, $gametypeLookupArray;
    $starttime = timer();

    $res = SQL_query("select TIME_FORMAT(starttime,'%H') time, count(*) count, sum(time) playtime from selectbf_playtimes group by time");

    $max = getMaxForField($res, "count");
    $sum = getSumForField($res, "count");

    $cols = SQL_fetchArray($res);

    $serverusage = [];
    for ($i = 0; $i < 24; $i++) {
        if ($i == $cols['time']) {
            $cols["playtime"]   = sec2time($cols["playtime"]);
            $cols['bar']        = statsbar($cols["count"], $max);
            $cols['percentage'] = sprintf("%01.2f", ($cols["count"] / $sum) * 100);
            array_push($serverusage, $cols);
            $cols = SQL_fetchArray($res);
        } else {
            $buf               = [];
            $buf["time"]       = $i;
            $buf["count"]      = 0;
            $buf["percentage"] = 0.00;
            $buf["playtime"]   = sec2time(0.0);
            $buf["bar"]        = statsbar(0, $max);
            array_push($serverusage, $buf);
        }
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getServerUsage</i></b> took $totaltime secs<br>");
    }

    return $serverusage;
}

function getLastGames($start)
{
    global $DEBUG;
    $starttime = timer();

    $res   = SQL_query("select servername,modid mod,game_mode,map,g.id,CONCAT(TIME_FORMAT(g.starttime,'%H:%i:%S '),DATE_FORMAT(g.starttime,'%d|%m|%Y')) date, count(*) rounds from selectbf_games g, selectbf_rounds r where g.id = r.game_id group by g.id order by g.starttime desc LIMIT $start,15");
    $games = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $id                     = $cols['id'];
        $cols["gamedetaillink"] = "game.php?id=$id";
        $cols["map"]            = clearUpText($cols['map'], "MAP");
        $cols['game_mode']      = clearUpText($cols['game_mode'], "GAME-MODE");
        $cols['modimg']         = getModSymbolForName($cols['mod']);
        array_push($games, $cols);
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getLastGames</i></b> took $totaltime secs<br>");
    }
    return $games;
}

function getTigerAsses()
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.name,p.id,count(*) count from selectbf_kills k, selectbf_players p where k.player_id = p.id and k.weapon='Tiger' group by p.name,p.id order by count DESC LIMIT 0,15");
    $max = getMaxForField($res, "count");

    $tigerasses = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        array_push($tigerasses, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getTigerAsses</i></b> took $totaltime secs<br>");
    }

    return $tigerasses;
}

function getRepairs()
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.id,p.name, sum(amount) amount, sum(repairtime) repairtime from selectbf_repairs r, selectbf_players p where player_id!=repair_player_id and r.player_id = p.id group by player_id order by amount DESC,repairtime DESC LIMIT 0,15");
    $max = getMaxForField($res, "amount");

    $repairs = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['amount'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        $cols['time']             = sec2time($cols['repairtime']);
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        array_push($repairs, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getRepairs</i></b> took $totaltime secs<br>");
    }

    return $repairs;
}

function getHeals()
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.id,p.name, sum(amount) amount, sum(healtime) healtime from selectbf_heals h, selectbf_players p where player_id!=healed_player_id and h.player_id = p.id group by player_id order by amount DESC,healtime DESC LIMIT 0,15");
    $max = getMaxForField($res, "amount");

    $heals = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['amount'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        $cols['time']             = sec2time($cols['healtime']);
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        array_push($heals, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getHeals</i></b> took $totaltime secs<br>");
    }

    return $heals;
}

function getWeaponUsage($id)
{
    global $DEBUG;
    $starttime = timer();

    $res        = SQL_oneRowQuery("select count(*) kills from selectbf_kills where player_id=$id");
    $totalkills = $res["kills"];

    $res = SQL_query("select weapon,count(*) count from selectbf_kills where player_id=$id group by weapon order by count desc LIMIT 0,10");
    $max = getMaxForField($res, "count");

    $types = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['weapon']     = clearUpText($cols['weapon'], "WEAPON");
        $cols['bar']        = statsbar($cols['count'], $max);
        $cols['percentage'] = sprintf("%01.2f", (($cols['count'] / $totalkills) * 100));
        array_push($types, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getWeaponUsage</i></b> took $totaltime secs<br>");
    }

    return $types;
}

function getCharacterTypeUsage($id)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select kit,count(*) count from selectbf_kits where player_id=$id group by kit order by count desc limit 0,10");
    $max = getMaxForField($res, "count");

    $types = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['modimg'] = getModSymbol($cols['kit'], "KIT");
        $cols['kit']    = clearUpText($cols['kit'], "KIT");
        $cols['bar']    = statsbar($cols['count'], $max);
        array_push($types, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getCharacterTypeUsage</i></b> took $totaltime secs<br>");
    }

    return $types;
}

function getCharacterTypes()
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select kit, times_used count, percentage from selectbf_cache_chartypeusage order by times_used desc Limit 0,15");
    $max = getMaxForField($res, "count");

    $types = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['modimg']     = getModSymbol($cols['kit'], "KIT");
        $cols['kit']        = clearUpText($cols['kit'], "KIT");
        $cols['bar']        = statsbar($cols['count'], $max);
        $cols['percentage'] = sprintf("%01.2f", $cols['percentage']);
        array_push($types, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getCharacterTypes</i></b> took $totaltime secs<br>");
    }

    return $types;
}

function getTopVictims($id)
{
    global $DEBUG;
    $starttime = timer();

    $Ergebnisse = SQL_oneRowQuery("select count(*) kills from selectbf_kills where player_id=$id");
    $totalkills = $Ergebnisse["kills"];

    $res = SQL_query("select victim_id id,p.name,count(*) count from selectbf_kills k, selectbf_players p where player_id=$id and k.victim_id = p.id group by victim_id  order by count desc Limit 0,10");
    $max = getMaxForField($res, "count");

    $victims = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols['percentage']       = sprintf("%01.2f", (($cols['count'] / $totalkills) * 100));
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        $cols["playerimg"]        = randomPlayerImg();
        array_push($victims, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getTopVictims</i></b> took $totaltime secs<br>");
    }

    return $victims;
}

function getTopAssasins($id)
{
    global $DEBUG;
    $starttime = timer();

    $Ergebnisse = SQL_oneRowQuery("select count(*) kills from selectbf_kills where victim_id=$id");
    $totalkills = $Ergebnisse["kills"];

    $res = SQL_query("select p.name,player_id id,count(*) count from selectbf_kills k, selectbf_players p where victim_id=$id and k.player_id = p.id group by player_id order by count DESC Limit 0,10");
    $max = getMaxForField($res, "count");

    $assasins = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols['percentage']       = sprintf("%01.2f", (($cols['count'] / $totalkills) * 100));
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        $cols["playerimg"]        = randomPlayerImg();
        array_push($assasins, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getTopAssasins</i></b> took $totaltime secs<br>");
    }

    return $assasins;
}

function getFavVehicles($id)
{
    global $DEBUG;
    $starttime = timer();

    $Ergebnisse = SQL_oneRowQuery("select sum(drivetime) drivetime from selectbf_drives where player_id=$id");
    $totaltime  = $Ergebnisse["drivetime"];

    $res = SQL_query("select vehicle,sum(drivetime) drivetime from selectbf_drives where player_id = $id group by vehicle order by drivetime DESC Limit 0,10");
    $max = getMaxForField($res, "drivetime");

    $vehicles = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['vehicle']    = clearUpText($cols['vehicle'], "VEHICLE");
        $cols['time']       = sec2time($cols["drivetime"]);
        $cols['percentage'] = sprintf("%01.2f", (($cols["drivetime"] / $totaltime) * 100));
        $cols['bar']        = statsbar($cols["drivetime"], $max, 100);
        array_push($vehicles, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getFavVehicles</i></b> took $totaltime secs<br>");
    }

    return $vehicles;
}

function getMapPerformance($id)
{
    global $DEBUG;
    $starttime = timer();

    $Ergebnisse = SQL_oneRowQuery("select sum(score) score from selectbf_playerstats where player_id = $id");
    $totalscore = $Ergebnisse["score"];

    $res = SQL_query("select sum(ps.score) score, g.map from selectbf_playerstats ps, selectbf_rounds r, selectbf_games g where player_id=$id and ps.round_id = r.id and r.game_id = g.id group by g.map having score != 0 order by score desc Limit 0,10");
    $max = getMaxForField($res, "score");

    $maps = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['map']           = clearUpText($cols['map'], "MAP");
        $cols['mapdetaillink'] = "map.php?map=" . $cols['map'];
        $cols['bar']           = statsbar($cols['score'], $max, 100);
        $cols['percentage']    = sprintf("%01.2f", (($cols['score'] / $totalscore) * 100));
        array_push($maps, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getMapPerformance</i></b> took $totaltime secs<br>");
    }

    return $maps;
}

function getHealTimes($id)
{
    global $DEBUG;
    $starttime = timer();

    $healtimes = [];

    $res = SQL_query("select sum(amount) amount, sum(healtime) healtime from selectbf_heals where player_id!=healed_player_id and player_id=$id");
    if ($Ergebnisse = SQL_fetchArray($res)) {
        $amount = $Ergebnisse["amount"];
        $time   = $Ergebnisse["healtime"];

        $healtimes["otherheals"] = ["amount" => $amount, "time" => sec2time($time)];
    } else {
        $amount = 0;
        $time   = 0;

        $healtimes["otherheals"] = ["amount" => $amount, "time" => sec2time($time)];
    }

    $res = SQL_query("select sum(amount) amount, sum(healtime) healtime from selectbf_heals where player_id=healed_player_id and player_id=$id");
    if ($Ergebnisse = SQL_fetchArray($res)) {
        $amount = $Ergebnisse["amount"];
        $time   = $Ergebnisse["healtime"];

        $healtimes["selfheals"] = ["amount" => $amount, "time" => sec2time($time)];
    } else {
        $amount = 0;
        $time   = 0;

        $healtimes["selfheals"] = ["amount" => $amount, "time" => sec2time($time)];
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getHealTimes</i></b> took $totaltime secs<br>");
    }

    return $healtimes;
}

function getRepairTimes($id)
{
    global $DEBUG;
    $starttime = timer();

    $repairtimes = [];

    $res = SQL_query("select sum(amount) amount, sum(repairtime) repairtime from selectbf_repairs where player_id!=repair_player_id and player_id=$id");
    if ($Ergebnisse = SQL_fetchArray($res)) {
        $amount = $Ergebnisse["amount"];
        $time   = $Ergebnisse["repairtime"];

        $repairtimes["otherrepairs"] = ["amount" => $amount, "time" => sec2time($time)];
    } else {
        $amount = 0;
        $time   = 0;

        $repairtimes["otherrepairs"] = ["amount" => $amount, "time" => sec2time($time)];
    }

    $res = SQL_query("select sum(amount) amount, sum(repairtime) repairtime from selectbf_repairs where player_id=repair_player_id and player_id=$id");
    if ($Ergebnisse = SQL_fetchArray($res)) {
        $amount = $Ergebnisse["amount"];
        $time   = $Ergebnisse["repairtime"];

        $repairtimes["repairs"] = ["amount" => $amount, "time" => sec2time($time)];
    } else {
        $amount = 0;
        $time   = 0;

        $repairtimes["otherrepairs"] = ["amount" => $amount, "time" => sec2time($time)];
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getRepairTimes</i></b> took $totaltime secs<br>");
    }

    return $repairtimes;
}

function getGameInfo($id)
{
    global $DEBUG, $gametypeLookupArray;
    $starttime = timer();

    $cols              = SQL_oneRowQuery("select servername,modid mod,mapid,map,game_mode,gametime,maxplayers,scorelimit,spawntime,soldierff,vehicleff,tkpunish,deathcamtype,CONCAT(TIME_FORMAT(starttime,'%H:%i:%S '),DATE_FORMAT(starttime,'%d|%m|%Y')) date from selectbf_games where id=$id");
    $cols["map"]       = clearUpText($cols['map'], "MAP");
    $cols['game_mode'] = clearUpText($cols['game_mode'], "GAME-MODE");
    $cols['modimg']    = getModSymbolForName($cols['mod']);

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getGameInfo</i></b> took $totaltime secs<br>");
    }

    return $cols;
}

function getRoundsForGame($id)
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query(
        "select id,start_tickets_team1,start_tickets_team2,CONCAT(TIME_FORMAT(starttime,'%H:%i:%S '),DATE_FORMAT(starttime,'%d|%m|%Y')) starttime,end_tickets_team1,end_tickets_team2,CONCAT(TIME_FORMAT(endtime,'%H:%i:%S '),DATE_FORMAT(endtime,'%d|%m|%Y')) endtime,endtype,winning_team from selectbf_rounds where game_id=$id order by selectbf_rounds.starttime ASC"
    );

    $rounds = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        if ($cols['endtype'] == "REGULAR") {
            $cols['topthree']  = getTopThreeForRound($cols['id']);
            $cols['axis']      = getTeamStatsForRoundAndTeam($cols['id'], "1");
            $cols['allied']    = getTeamStatsForRoundAndTeam($cols['id'], "2");
            $cols['isRegular'] = true;
        } elseif ($cols['endtype'] == "RESTART") {
            $cols['isRestart'] = true;
        } elseif ($cols['endtype'] == "FORCED") {
            $cols['isForced'] = true;
        }
        if ($cols["winning_team"] == 1) {
            $cols["axis_won"] = true;
        } elseif ($cols["winning_team"] == 2) {
            $cols["allies_won"] = true;
        } else {
            $cols["nobody_won"] = true;
        }

        array_push($rounds, $cols);
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getRoundsForGame</i></b> took $totaltime secs<br>");
    }

    return $rounds;
}

function getTopThreeForRound($id)
{
    global $DEBUG, $TMPL_CFG_GOLD_STAR_IMG, $TMPL_CFG_SILVER_STAR_IMG, $TMPL_CFG_BRONZE_STAR_IMG, $TEMPLATE_DIR, $TMPL_CFG_AXIS_IMG, $TMPL_CFG_ALLIED_IMG;
    $starttime = timer();

    $res = SQL_query(
        "select p.name, p.id, team, score, kills, deaths, tks, captures, attacks, defences, objectives, objectivetks, heals,selfheals,repairs,otherrepairs,first, second, third from selectbf_playerstats ps, selectbf_players p where ps.player_id = p.id and ps.round_id=$id order by score DESC LIMIT 0,3"
    );

    $topthree = [];

    $i = 0;
    while (false !== ($cols = SQL_fetchArray($res))) {
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        $cols["playerimg"]        = randomPlayerImg();

        if ($cols['team'] == 1) {
            $cols['TeamFlag'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_AXIS_IMG;
        } else {
            $cols['TeamFlag'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_ALLIED_IMG;
        }

        if ($i == 0) {
            $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_GOLD_STAR_IMG;
        } elseif ($i == 1) {
            $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_SILVER_STAR_IMG;
        } elseif ($i == 2) {
            $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_BRONZE_STAR_IMG;
        }
        array_push($topthree, $cols);
        $i++;
    }

    if ($i == 0) {
        $topthree = null;
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getTopThreeForRound</i></b> took $totaltime secs<br>");
    }

    return $topthree;
}

function getTeamStatsForRoundAndTeam($id, $team)
{
    global $DEBUG, $TMPL_CFG_GOLD_STAR_IMG, $TMPL_CFG_SILVER_STAR_IMG, $TMPL_CFG_BRONZE_STAR_IMG, $TEMPLATE_DIR, $TMPL_CFG_AXIS_IMG, $TMPL_CFG_ALLIED_IMG;
    $starttime = timer();

    $res = SQL_query(
        "select p.name, p.id, team, score, kills, deaths, tks, captures, attacks, defences, objectives, objectivetks, heals,selfheals,repairs,otherrepairs,first, second, third from selectbf_playerstats ps, selectbf_players p where ps.player_id = p.id and ps.round_id=$id and team=$team order by score DESC"
    );

    $topthree = [];

    $i = 0;
    while (false !== ($cols = SQL_fetchArray($res))) {
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        $cols["playerimg"]        = randomPlayerImg();

        if ($cols['team'] == 1) {
            $cols['TeamFlag'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_AXIS_IMG;
        } else {
            $cols['TeamFlag'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_ALLIED_IMG;
        }

        if ($cols['first'] == "1") {
            $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_GOLD_STAR_IMG;
        } elseif ($cols['second'] == "1") {
            $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_SILVER_STAR_IMG;
        } elseif ($cols['third'] == "1") {
            $cols['starimg'] = $cols['starimg'] = "templates/" . $TEMPLATE_DIR . "/images/" . $TMPL_CFG_BRONZE_STAR_IMG;
        }
        array_push($topthree, $cols);

        $i++;
    }

    if ($i == 0) {
        $topthree = null;
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getTeamStatsForRoundAndTeam</i></b> took $totaltime secs<br>");
    }

    return $topthree;
}

function getWingsOfFury()
{
    global $DEBUG;
    $starttime = timer();

    $res = SQL_query("select p.name,p.id,count(*) count from selectbf_kills k, selectbf_players p where k.player_id = p.id and k.weapon in ('BF109','Chi-ha','AichiVal','SBD','Corsair','Zero','Spitfire','Ju88A','B17','Stuka','Yak9') group by p.name,p.id order by count DESC LIMIT 0,15");
    $max = getMaxForField($res, "count");

    $wings = [];
    while (false !== ($cols = SQL_fetchArray($res))) {
        $cols['bar']              = statsbar($cols['count'], $max);
        $cols["playerimg"]        = randomPlayerImg();
        $id                       = $cols['id'];
        $cols["playerdetaillink"] = "player.php?id=$id";
        array_push($wings, $cols);
    }
    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getWingsOfFury</i></b> took $totaltime secs<br>");
    }

    return $wings;
}

function getLinkForIndexColumn($column, $orderby, $direction)
{
    global $DEBUG;
    $starttime = timer();

    $str = "index.php?orderby=$column";

    if ($column == $orderby) {
        if ($direction == "asc") {
            $str = "$str&direction=desc";
        } else {
            $str = "$str&direction=asc";
        }
    } else {
        $str = "$str&direction=desc";
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 1) {
        echo("<b><i>getLinkForIndexColumn($column)</i></b> took $totaltime secs<br>");
    }

    return $str;
}

function getLastGamesNavBar($totalgamecount, $start)
{
    global $DEBUG;
    $starttime = timer();

    $step       = $start / 15;
    $totalsteps = $totalgamecount / 15;

    $navbarinfo = [];

    for ($i = 0; $i <= $totalsteps; $i++) {
        $text     = "";
        $isLinked = false;
        $link     = "";

        if ($i == $step) {
            $text     = "$i";
            $isLinked = false;
            $link     = "";
        } else {
            if ($totalsteps > 25) {
                if ($i == $step - 10) {
                    $text     = "...";
                    $isLinked = 0;
                    $link     = "";
                }
                if ($i >= $step - 10 && $i <= $step + 10) {
                    $text     = "$i";
                    $buf      = $i * 15;
                    $isLinked = 1;
                    $link     = "index.php?startgames=$buf";
                }
                if ($i == $step + 10) {
                    $text     = "...";
                    $isLinked = 0;
                    $link     = "";
                }
            } else {
                $text     = "$i";
                $buf      = $i * 15;
                $isLinked = 1;
                $link     = "index.php?startgames=$buf";
            }
        }
        if ($text != "") {
            array_push($navbarinfo, ["text" => $text, "isLinked" => $isLinked, "link" => $link]);
        }
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 1) {
        echo("<b><i>getLastGamesNavBar</i></b> took $totaltime secs<br>");
    }

    return $navbarinfo;
}

function getRankingNavBar($totalplayercount, $start, $orderby, $direction)
{
    global $DEBUG;
    $starttime = timer();

    $step       = $start / 50;
    $totalsteps = $totalplayercount / 50;

    $navbarinfo = [];

    for ($i = 0; $i <= $totalsteps; $i++) {
        $text     = "";
        $isLinked = false;
        $link     = "";

        if ($i == $step) {
            $text     = "$i";
            $isLinked = false;
            $link     = "";
        } else {
            if ($totalsteps > 25) {
                if ($i == $step - 10) {
                    $text     = "...";
                    $isLinked = 0;
                    $link     = "";
                }
                if ($i >= $step - 10 && $i <= $step + 10) {
                    $text     = "$i";
                    $buf      = $i * 50;
                    $isLinked = 1;
                    $link     = "index.php?orderby=$orderby&start=$buf&direction=$direction";
                }
                if ($i == $step + 10) {
                    $text     = "...";
                    $isLinked = 0;
                    $link     = "";
                }
            } else {
                $text     = "$i";
                $buf      = $i * 50;
                $isLinked = 1;
                $link     = "index.php?orderby=$orderby&start=$buf&direction=$direction";
            }
        }
        if ($text != "") {
            array_push($navbarinfo, ["text" => $text, "isLinked" => $isLinked, "link" => $link]);
        }
    }

    $totaltime = timer() - $starttime;
    if ($DEBUG > 1) {
        echo("<b><i>getRankingNavBar</i></b> took $totaltime secs<br>");
    }

    return $navbarinfo;
}

function getPlayerInfo($id)
{
    global $DEBUG;
    $starttime = timer();

    $infos = [];

    $aResult       = SQL_oneRowQuery("select name,CONCAT(TIME_FORMAT(inserttime,'%H:%i:%S '),DATE_FORMAT(inserttime,'%d|%m|%Y')) date from selectbf_players where id=$id");
    $infos["name"] = $aResult["name"];
    $infos["date"] = $aResult["date"];

    $Ergebnisse      = SQL_oneRowQuery("select count(*)count from selectbf_playerstats where player_id=$id");
    $infos["rounds"] = $Ergebnisse["count"];

    $Ergebnisse      = SQL_oneRowQuery(
        "select p.name,ps.player_id id, sum(score) score ,sum(kills) kills ,sum(deaths) deaths ,sum(tks) tks ,sum(captures) captures ,sum(attacks) attacks ,sum(defences) defences ,sum(objectives) objetives,sum(objectivetks) objetivetks,sum(heals) heals,sum(selfheals) selfheals ,sum(repairs) repairs ,sum(otherrepairs) otherrepairs,count(*) rounds, sum(first) first, sum(second) second, sum(third) third from selectbf_playerstats ps, selectbf_players p where ps.player_id = p.id and p.id = $id group by p.name,ps.player_id "
    );
    $infos["score"]  = $Ergebnisse["score"];
    $infos["kills"]  = $Ergebnisse["kills"];
    $infos["deaths"] = $Ergebnisse["deaths"];
    $infos["tks"]    = $Ergebnisse["tks"];
    $infos["first"]  = $Ergebnisse["first"];
    $infos["second"] = $Ergebnisse["second"];
    $infos["third"]  = $Ergebnisse["third"];

    $totaltime = timer() - $starttime;
    if ($DEBUG > 0) {
        echo("<b><i>getPlayerInfo</i></b> took $totaltime secs<br>");
    }

    return $infos;
}

?>
