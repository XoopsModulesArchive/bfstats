<?

$Erg       = SQL_oneRowQuery("SELECT p.id,p.name, sum(amount) amount, sum(repairtime) repairtime FROM selectbf_repairs r, selectbf_players p WHERE player_id!=repair_player_id AND r.player_id = p.id GROUP BY player_id ORDER BY amount DESC,repairtime DESC LIMIT 0,1");
$toprepair = $Erg["id"];

$Erg     = SQL_oneRowQuery("SELECT p.id,p.name, sum(amount) amount, sum(healtime) healtime FROM selectbf_heals h, selectbf_players p WHERE player_id!=healed_player_id AND h.player_id = p.id GROUP BY player_id ORDER BY amount DESC,healtime DESC LIMIT 0,1");
$topheal = $Erg["id"];

$Erg      = SQL_oneRowQuery("SELECT ps.player_id id, ((sum(kills)/sum(deaths))+(sum(attacks)*2/count(*))-(sum(tks)/count(*))+(sum(captures)*10/count(*)))*100 points FROM selectbf_playerstats ps GROUP BY ps.player_id ORDER BY points DESC LIMIT 0,1");
$toppoint = $Erg["id"];

$Erg      = SQL_oneRowQuery("SELECT ps.player_id id, sum(score) score FROM selectbf_playerstats ps GROUP BY ps.player_id ORDER BY score DESC LIMIT 0,1");
$topscore = $Erg["id"];

$Erg        = SQL_oneRowQuery("SELECT value FROM selectbf_admin WHERE name='STARS'");
$starnumber = $Erg["value"];
}

function getAwards($player_id, $first, $second, $third)
{
    global $toprepair, $topheal, $toppoint, $topscore, $starnumber;
    $str = "";

    if ($first > $starnumber) {
        $str = "$str<img src=images/symbols/star_gold.gif alt=\"Rank 1: $first times\">x$first&nbsp;";
    } else {
        for ($i = 0; $i < $first; $i++) {
            $str = "$str<img src=images/symbols/star_gold.gif alt=\"Rank 1: $first times\">";
        }
    }
    if ($second > $starnumber) {
        $str = "$str<img src=images/symbols/star_silver.gif alt=\"Rank 2: $second times\">x$second&nbsp;";
    } else {
        for ($i = 0; $i < $second; $i++) {
            $str = "$str<img src=images/symbols/star_silver.gif alt=\"Rank 2: $second times\">";
        }
    }
    if ($third > $starnumber) {
        $str = "$str<img src=images/symbols/star_bronze.gif alt=\"Rank 3: $third times\">x$third&nbsp;";
    } else {
        for ($i = 0; $i < $third; $i++) {
            $str = "$str<img src=images/symbols/star_bronze.gif alt=\"Rank 3: $third times\">";
        }
    }
    if ($topheal == $player_id) {
        $str = "$str <img src=images/symbols/top-heal.gif alt=\"TOP-Medic\">";
    }
    if ($toprepair == $player_id) {
        $str = "$str <img src=images/symbols/top-repair.gif alt=\"TOP-Engineer\">";
    }
    if ($toppoint == $player_id) {
        $str = "$str <img src=images/symbols/top-point.gif alt=\"TOP-Point\">";
    }
    if ($topscore == $player_id) {
        $str = "$str <img src=images/symbols/top-score.gif alt=\"TOP-Score\">";
    }

    echo $str;
}

?>
