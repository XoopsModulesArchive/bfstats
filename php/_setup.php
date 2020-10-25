<?

require_once "include/vLib/vlibTemplate.php";
require_once "include/sql.php";

function createAllTables()
{
    SQL_query("CREATE TABLE selectbf_admin (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(50) DEFAULT NULL,  value VARCHAR(100) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;");
    SQL_query("CREATE TABLE selectbf_attacks (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id)) ENGINE = ISAM;");
    SQL_query("CREATE TABLE selectbf_cache_chartypeusage (  kit VARCHAR(35) NOT NULL DEFAULT '',  percentage FLOAT DEFAULT NULL,  times_used INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (kit)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_cache_mapstats (  map VARCHAR(100) NOT NULL DEFAULT '0',  wins_team1 INT(10) UNSIGNED DEFAULT NULL,  wins_team2 INT(10) UNSIGNED DEFAULT NULL,  win_team1_tickets_team1 FLOAT DEFAULT NULL,  win_team1_tickets_team2 FLOAT DEFAULT NULL,  win_team2_tickets_team1 FLOAT DEFAULT NULL,  win_team2_tickets_team2 FLOAT DEFAULT NULL,  score_team1 INT(10) UNSIGNED DEFAULT NULL,  score_team2 INT(10) UNSIGNED DEFAULT NULL,  kills_team1 INT(10) UNSIGNED DEFAULT NULL,  kills_team2 INT(10) UNSIGNED DEFAULT NULL,  deaths_team1 INT(10) UNSIGNED DEFAULT NULL,  deaths_team2 INT(10) UNSIGNED DEFAULT NULL,  attacks_team1 INT(10) UNSIGNED DEFAULT NULL,  attacks_team2 INT(10) UNSIGNED DEFAULT NULL,  captures_team1 INT(10) UNSIGNED DEFAULT NULL,  captures_team2 INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (map),  KEY map (map)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_cache_ranking (  rank INT(10) UNSIGNED DEFAULT NULL,  player_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  playername VARCHAR(100) DEFAULT NULL,  score INT(10) UNSIGNED DEFAULT NULL,  kills INT(10) UNSIGNED DEFAULT NULL,  deaths INT(10) UNSIGNED DEFAULT NULL,  kdrate DOUBLE DEFAULT NULL,  score_per_minute DOUBLE DEFAULT NULL,  tks INT(10) UNSIGNED DEFAULT NULL,  captures INT(10) UNSIGNED DEFAULT NULL,  attacks INT(10) UNSIGNED DEFAULT NULL,  defences INT(10) UNSIGNED DEFAULT NULL,  objectives INT(10) UNSIGNED DEFAULT NULL,  objectivetks INT(10) UNSIGNED DEFAULT NULL,  heals INT(10) UNSIGNED DEFAULT NULL,  selfheals INT(10) UNSIGNED DEFAULT NULL,  repairs TINYINT(3) UNSIGNED DEFAULT NULL,  otherrepairs TINYINT(3) UNSIGNED DEFAULT NULL,  first TINYINT(3) UNSIGNED DEFAULT NULL,  second TINYINT(3) UNSIGNED DEFAULT NULL,  third TINYINT(3) UNSIGNED DEFAULT NULL,  playtime DOUBLE DEFAULT NULL,  rounds_played INT(10) UNSIGNED DEFAULT NULL,  last_visit DATETIME DEFAULT NULL,  PRIMARY KEY  (player_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_cache_vehicletime (  vehicle VARCHAR(100) NOT NULL DEFAULT '',  time FLOAT DEFAULT NULL,  percentage_time FLOAT DEFAULT NULL,  times_used INT(10) UNSIGNED DEFAULT NULL,  percentage_usage FLOAT DEFAULT NULL,  PRIMARY KEY  (vehicle),  KEY vehicle (vehicle)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_cache_weaponkills (  weapon VARCHAR(50) NOT NULL DEFAULT '',  kills INT(10) UNSIGNED DEFAULT NULL,  percentage FLOAT DEFAULT NULL,  PRIMARY KEY  (weapon),  KEY weapon (weapon)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_category (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(50) DEFAULT NULL,  collect_data INT(10) UNSIGNED DEFAULT NULL,  datasource_name VARCHAR(50) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_categorymember (  member VARCHAR(50) DEFAULT NULL,  category INT(10) UNSIGNED DEFAULT NULL) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_cleartext (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  original VARCHAR(50) DEFAULT NULL,  custom VARCHAR(100) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_deaths (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_drives (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  vehicle VARCHAR(100) DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  drivetime FLOAT DEFAULT NULL,  round_id INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,round_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_games (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  servername VARCHAR(250) DEFAULT NULL,  modid VARCHAR(50) DEFAULT NULL,  mapid VARCHAR(50) DEFAULT NULL,  map VARCHAR(150) DEFAULT NULL,  game_mode VARCHAR(50) DEFAULT NULL,  gametime INT(10) UNSIGNED DEFAULT NULL,  maxplayers INT(10) UNSIGNED DEFAULT NULL,  scorelimit INT(10) UNSIGNED DEFAULT NULL,  spawntime INT(10) UNSIGNED DEFAULT NULL,  soldierff INT(10) UNSIGNED DEFAULT NULL,  vehicleff INT(10) UNSIGNED DEFAULT NULL,  tkpunish TINYINT(3) UNSIGNED DEFAULT NULL,  deathcamtype TINYINT(3) UNSIGNED DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_heals (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  healed_player_id INT(10) UNSIGNED DEFAULT NULL,  amount INT(10) UNSIGNED DEFAULT NULL,  healtime FLOAT DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,round_id,healed_player_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_kills (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  weapon VARCHAR(50) DEFAULT NULL,  victim_id INT(10) UNSIGNED DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,victim_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_kits (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  kit VARCHAR(35) DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,round_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_modassignment (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  item VARCHAR(50) DEFAULT NULL,  mod VARCHAR(50) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_params (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(50) DEFAULT NULL,  value VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;");
    SQL_query("CREATE TABLE selectbf_players (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(150) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_playerstats (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  team TINYINT(3) UNSIGNED DEFAULT NULL,  score INT(10) UNSIGNED DEFAULT NULL,  kills INT(10) UNSIGNED DEFAULT NULL,  deaths INT(10) UNSIGNED DEFAULT NULL,  tks INT(10) UNSIGNED DEFAULT NULL,  captures INT(10) UNSIGNED DEFAULT NULL,  attacks INT(10) UNSIGNED DEFAULT NULL,  defences INT(10) UNSIGNED DEFAULT NULL,  objectives INT(10) UNSIGNED DEFAULT NULL,  objectivetks INT(10) UNSIGNED DEFAULT NULL,  heals INT(10) UNSIGNED DEFAULT NULL,  selfheals INT(10) UNSIGNED DEFAULT NULL,  repairs TINYINT(3) UNSIGNED DEFAULT NULL,  otherrepairs TINYINT(3) UNSIGNED DEFAULT NULL,  round_id INT(10) UNSIGNED DEFAULT NULL,  first TINYINT(3) UNSIGNED DEFAULT NULL,  second TINYINT(3) UNSIGNED DEFAULT NULL,  third TINYINT(3) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,round_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_playtimes (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  time FLOAT DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_repairs (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  repair_player_id INT(10) UNSIGNED DEFAULT NULL,  vehicle VARCHAR(100) DEFAULT NULL,  amount INT(10) UNSIGNED DEFAULT NULL,  repairtime FLOAT DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,round_id,repair_player_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_rounds (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  start_tickets_team1 INT(10) UNSIGNED DEFAULT NULL,  start_tickets_team2 INT(10) UNSIGNED DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  end_tickets_team1 INT(10) UNSIGNED DEFAULT NULL,  end_tickets_team2 INT(10) UNSIGNED DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  endtype TINYTEXT,  winning_team TINYINT(3) UNSIGNED DEFAULT '0',  game_id INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,game_id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_selfkills (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_tks (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  weapon VARCHAR(100) DEFAULT NULL,  victim_id INT(10) UNSIGNED DEFAULT NULL,  time DATETIME DEFAULT NULL,  round_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id,victim_id)) ENGINE = ISAM;"
    );
}

function addNeededValues($password)
{
    SQL_query("INSERT INTO selectbf_admin (name, value, inserttime) VALUES('ADMIN_PSW', '$password', now());");
    SQL_query("INSERT INTO selectbf_admin (name, value, inserttime) VALUES('VERSION', '0.3', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('TEMPLATE', 'default', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('DEBUG-LEVEL', '0', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('TITLE-PREFIX', 'select(bf)', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('MIN-ROUNDS', '0', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('STAR-NUMBER', '20', now());");
    SQL_query("INSERT INTO selectbf_params (name, value, inserttime) VALUES ('RANK-ORDERBY', 'score', now());");
}

function dropAllTables()
{
    SQL_query("DROP TABLE selectbf_admin");
    SQL_query("DROP TABLE selectbf_attacks");
    SQL_query("DROP TABLE selectbf_cache_chartypeusage");
    SQL_query("DROP TABLE selectbf_cache_mapstats");
    SQL_query("DROP TABLE selectbf_cache_ranking");
    SQL_query("DROP TABLE selectbf_cache_vehicletime");
    SQL_query("DROP TABLE selectbf_cache_weaponkills");
    SQL_query("DROP TABLE selectbf_category");
    SQL_query("DROP TABLE selectbf_categorymember");
    SQL_query("DROP TABLE selectbf_cleartext");
    SQL_query("DROP TABLE selectbf_deaths");
    SQL_query("DROP TABLE selectbf_drives");
    SQL_query("DROP TABLE selectbf_games");
    SQL_query("DROP TABLE selectbf_heals");
    SQL_query("DROP TABLE selectbf_kills");
    SQL_query("DROP TABLE selectbf_kits");
    SQL_query("DROP TABLE selectbf_modassignment");
    SQL_query("DROP TABLE selectbf_params");
    SQL_query("DROP TABLE selectbf_players");
    SQL_query("DROP TABLE selectbf_playerstats");
    SQL_query("DROP TABLE selectbf_playtimes");
    SQL_query("DROP TABLE selectbf_repairs");
    SQL_query("DROP TABLE selectbf_rounds");
    SQL_query("DROP TABLE selectbf_selfkills");
    SQL_query("DROP TABLE selectbf_tks");
}

function updateDatamodell()
{
    SQL_query("TRUNCATE selectbf_admin");
    SQL_query("CREATE TABLE selectbf_cache_chartypeusage (  kit VARCHAR(35) NOT NULL DEFAULT '',  percentage FLOAT DEFAULT NULL,  times_used INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (kit)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_cache_mapstats (  map VARCHAR(100) NOT NULL DEFAULT '0',  wins_team1 INT(10) UNSIGNED DEFAULT NULL,  wins_team2 INT(10) UNSIGNED DEFAULT NULL,  win_team1_tickets_team1 FLOAT DEFAULT NULL,  win_team1_tickets_team2 FLOAT DEFAULT NULL,  win_team2_tickets_team1 FLOAT DEFAULT NULL,  win_team2_tickets_team2 FLOAT DEFAULT NULL,  score_team1 INT(10) UNSIGNED DEFAULT NULL,  score_team2 INT(10) UNSIGNED DEFAULT NULL,  kills_team1 INT(10) UNSIGNED DEFAULT NULL,  kills_team2 INT(10) UNSIGNED DEFAULT NULL,  deaths_team1 INT(10) UNSIGNED DEFAULT NULL,  deaths_team2 INT(10) UNSIGNED DEFAULT NULL,  attacks_team1 INT(10) UNSIGNED DEFAULT NULL,  attacks_team2 INT(10) UNSIGNED DEFAULT NULL,  captures_team1 INT(10) UNSIGNED DEFAULT NULL,  captures_team2 INT(10) UNSIGNED DEFAULT NULL,  PRIMARY KEY  (map),  KEY map (map)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_cache_ranking (  rank INT(10) UNSIGNED DEFAULT NULL,  player_id INT(10) UNSIGNED NOT NULL DEFAULT '0',  playername VARCHAR(100) DEFAULT NULL,  score INT(10) UNSIGNED DEFAULT NULL,  kills INT(10) UNSIGNED DEFAULT NULL,  deaths INT(10) UNSIGNED DEFAULT NULL,  kdrate DOUBLE DEFAULT NULL,  score_per_minute DOUBLE DEFAULT NULL,  tks INT(10) UNSIGNED DEFAULT NULL,  captures INT(10) UNSIGNED DEFAULT NULL,  attacks INT(10) UNSIGNED DEFAULT NULL,  defences INT(10) UNSIGNED DEFAULT NULL,  objectives INT(10) UNSIGNED DEFAULT NULL,  objectivetks INT(10) UNSIGNED DEFAULT NULL,  heals INT(10) UNSIGNED DEFAULT NULL,  selfheals INT(10) UNSIGNED DEFAULT NULL,  repairs TINYINT(3) UNSIGNED DEFAULT NULL,  otherrepairs TINYINT(3) UNSIGNED DEFAULT NULL,  first TINYINT(3) UNSIGNED DEFAULT NULL,  second TINYINT(3) UNSIGNED DEFAULT NULL,  third TINYINT(3) UNSIGNED DEFAULT NULL,  playtime DOUBLE DEFAULT NULL,  rounds_played INT(10) UNSIGNED DEFAULT NULL,  last_visit DATETIME DEFAULT NULL,  PRIMARY KEY  (player_id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_cache_vehicletime (  vehicle VARCHAR(100) NOT NULL DEFAULT '',  time FLOAT DEFAULT NULL,  percentage_time FLOAT DEFAULT NULL,  times_used INT(10) UNSIGNED DEFAULT NULL,  percentage_usage FLOAT DEFAULT NULL,  PRIMARY KEY  (vehicle),  KEY vehicle (vehicle)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_cache_weaponkills (  weapon VARCHAR(50) NOT NULL DEFAULT '',  kills INT(10) UNSIGNED DEFAULT NULL,  percentage FLOAT DEFAULT NULL,  PRIMARY KEY  (weapon),  KEY weapon (weapon)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_category (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(50) DEFAULT NULL,  collect_data INT(10) UNSIGNED DEFAULT NULL,  datasource_name VARCHAR(50) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_categorymember (  member VARCHAR(50) DEFAULT NULL,  category INT(10) UNSIGNED DEFAULT NULL) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_cleartext (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  original VARCHAR(50) DEFAULT NULL,  custom VARCHAR(100) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query(
        "CREATE TABLE selectbf_modassignment (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  item VARCHAR(50) DEFAULT NULL,  mod VARCHAR(50) DEFAULT NULL,  type VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;"
    );
    SQL_query("CREATE TABLE selectbf_params (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  name VARCHAR(50) DEFAULT NULL,  value VARCHAR(50) DEFAULT NULL,  inserttime DATETIME DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id)) ENGINE = ISAM;");
    SQL_query(
        "CREATE TABLE selectbf_playtimes (  id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,  player_id INT(10) UNSIGNED DEFAULT NULL,  starttime DATETIME DEFAULT NULL,  endtime DATETIME DEFAULT NULL,  time FLOAT DEFAULT NULL,  PRIMARY KEY  (id),  UNIQUE KEY id (id),  KEY id_2 (id,player_id)) ENGINE = ISAM;"
    );
}

//now start setting the variables for the Template
$tmpl = new vlibTemplate("templates/default/_setup.html");

@$todo = $_REQUEST["todo"];

if (isset($todo)) {
    switch ($todo) {
        case "scratch":
            @$password = $_REQUEST["password"];
            @$confirm = $_REQUEST["confirm"];

            if ($password == $confirm) {
                $password = md5($password);
                createAllTables();
                addNeededValues($password);
                $tmpl->setVar("msg", "<b>All Tables created!</b><br><b>Default-Parameter successfully written!</b><br>");
                $tmpl->setVar("done", true);
            } else {
                $tmpl->setVar("error", "<b>Password</b> and <b>Confirm</b> didin't match!");
            }
            break;

        case "update":
            $cols = SQL_oneRowQuery("SELECT value FROM selectbf_admin WHERE name='ADMIN_PSW'");
            $password = md5($cols["value"]);
            updateDatamodell();
            addNeededValues($password);
            $tmpl->setVar("msg", "Datamodell-Update <b>successful!</b><br>New Default-Values set!");
            $tmpl->setVar("done", true);
            break;
        case "reset":
            if (isset($_REQUEST["sure"]) && isset($_REQUEST["reallysure"])) {
                dropAllTables();
                $tmpl->setVar("msg", "<b>All Tables dropped!</b>");
                $tmpl->setVar("deleted", true);
            } else {
                $tmpl->setVar("error", "Please first be <b>sure</b> and <b>really sure</b> before you push buttons!");
            }
            break;
    }
}

@$tmpl->pparse();

?>



