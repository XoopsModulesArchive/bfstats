<? xml version = "1.0" encoding = "iso-8859-1"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- $Id: doc.html,v 1.31 2002/07/19 07:38:17 cvs_iezzi Exp $ -->
<head>
    <title>Xoops BattleField 1942 Stats Logger Documentation</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Content-Language" content="en">
    <meta name="Copyright" content="2000-2002, PHPEE.COM">
    <meta name="AUTHOR" content="Philip IEZZI">
    <meta name="Description" content="Power Phlogger - your ultimative counter hosting tool">
    <meta name="Keywords"
          content="PHP, Phlogger, Power Phlogger, PPhlogger, Philip, Philip Iezzi, Counter, visitor tracking, visitor analysis, web statistics, Free-counter, free counter, script, counter hosting, opensource, open-source, gnu, GPL, website statistics, statistics, php-scripts, php-script, tracker, logger, log, track, Zurich, Zürich, Fifi, Pipo, Philippo, freelogger, freelogger.com, logging tool, Apache, Linux, mySQL, free, Rocco, soraxis">
    <style type="text/css">
        <!--
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt
        }

        h1, h2, h3, h4 {
            color: #6388ff;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
        }

        a {
            color: #6388ff;
            font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
            font-weight: bold;
            text-decoration: none;
        }

        a:HOVER {
            color: #000099;
            background-color: #99CCFF;
            font-weight: bolder;
        }

        .PPcode {
            background-color: #eeeeee;
            margin-left: 20pt;
            padding: 4;
            font-size: 9pt;
            text-align: left;
        }

        .warning {
            color: red;
        }

        -->
    </style>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<!--

    ************************************************************
    *                                                          *
    *  PowerPhlogger is distributed under the terms of the     *
    *  GNU Public License                                      *
    *                                                          *
    *  (c) 2000-2002 WWW.PHPEE.COM                             *
    *  All rights reserved.                                    *
    *                                                          *
    ************************************************************


                         \\\\|////
                         \\ _ _ //
                          ( o o )
       +===============oOOo-(_)-oOOo===========+
                     www.phpee.com
         (c)Philip Iezzi [webmaster@phpee.com]
       +=======================Oooo============+
                         oooO  (  )
                         (  )  ) /
                          \ ( (_/
                           \_)
-->
<h1>Xoops BattleField 1942 Stats Logger documentation</h1>
<p>Complete stats logging tool</p>
<p>Original Script description</p>
<p><em><strong>&quot;select(bf) is a tool to parse the XML-logfiles generated
            by the Battlefield 1942 server and every server that is running a Battlefield
            1942-based modification (like DesertCombat).</strong></em></p>
<p><strong><em>select(bf) uses Java-Technology to analyze the XML-logfiles and
            saves them to a MySQL database. Together with this analyze functionality PHP
            script-files are supplied that view the information gathered from the log-files
            as HTML-statistics pages.</em></strong></p>
<p><strong><em>For that reason select(bf) should be able to run on any desired
            OS (Windows, Linux, Unix...). The only platform dependend part will be a batch-file,
            which is most likely easy replaced.&quot;</em></strong></p>
<p><i></i></p>
<ul>
    <li><a href="#requirements">Requirements</a></li>
    <li><a href="#install">Installation</a></li>
    <li><a href="#help">Help</a></li>
</ul>
<p>This is no complete documentation of the features and functionality of PowerPhlogger.
    It just covers parts of it and is always getting updated. If you wish to add
    a section or complete an incomplete section, you're welcome to take part of
    this documentation. Just send me your changes and I'm going to include them
    in this file.</p>
<p>
    Local documents: <a href="../../pphlogger/history.txt">CHANGELOG</a>
    | <a href="../../pphlogger/readme.txt">README</a>
    | <a href="../../pphlogger/license.txt">LICENSE</a></p>
<hr noshade="noshade">
<a name="requirements" id="requirements"></a>
<h2>Requirements</h2>

<p>What you need to run this thing </p>
<ul>
    <li>PARSER<br>
        - Java Runtime Environment 1.4 or higher installed it is available freely
        at http://java.sun.com Be aware to download their offline-installations. The
        online-installations are somewhat creepy. You can also install the Java Software
        Development Kit 1.4 or higher it comes with the JRE. I have provided you a
        link directly to the location to download the JRE. I had a tough time deciphering
        what I needed to download to get this to work. <a href="https://jsecom15a.sun.com/ECom/EComActionServlet;jsessionid=jsecom15a.sun.com-1c%3A4050a286%3A1a7aa01b52af1cf">https://jsecom15a.sun.com/ECom/EComActionServlet;jsessionid=jsecom15a.sun.com-1c%3A4050a286%3A1a7aa01b52af1cf</a></li>
</ul>
<ul>
    <li>WEBSERVER<br>
        - PHP 4.3.2 http://www.php.net<br>
        NOTICE: This the version that select(bf) has been tested with. It will probably
        work with earlier<br>
        versions also.
    </li>
    <li>DATABASE<br>
        - MySQL 4.0.13 http://www.mysql.com<br>
        NOTICE: This the version that select(bf) has been tested with. It will probably
        work with earlier<br>
        versions also.
    </li>
</ul>
<hr noshade="noshade">
<a name="install" id="install"></a>
<h2>Installation</h2>
<p>I. Step One<br>
    First uncompress the contents of the archive you downloaded to a desired directory.</p>
<p>Archive structure:<br>
    bin - Contains the binaries of select(bf), the config-file and batch-files to
    run it.<br>
    lib - Contains the &quot;connector/j&quot;, &quot;jdom&quot; and &quot;commons-net&quot;
    libraries needed to run the selectbf-parser.<br>
    php - Contains all the files that you later shoot into your webserver<br>
    src - Contains the source-files of the select(bf)-parser</p>
<p><br>
    II. Step Two<br>
    Create a database for the data that select(bf) collects, or gather choose one
    that you <br>
    already have. You'll need the authentication and connect information. </p>
<p>Adjusting the parser-config (whatever you do in here. Don't mess up the config-files
    XML structure<br>
    or the parser will not be able to read it)</p>
<p>- Open the &quot;config.xml&quot; file in the &quot;bin&quot;-directory. </p>
<p>- Now you'll have to set the options you want in this file (its the config
    for the parser)<br>
    <br>
    -&gt; Set all database connection information to those of the database you want
    the parser to use.<br>
    <br>
    -&gt; Set die &quot;after-parsing&quot; to one of the following values:<br>
    &quot;remain&quot; - for leaving the files where they are after parse<br>
    &quot;rename&quot; - to add &quot;.parsed&quot; to the end of every parsed file<br>
    &quot;delete&quot; - to delete the file after parsing</p>
<p> -&gt; Set die &quot;after-download&quot; to one of the following values:<br>
    &quot;remain&quot; - for leaving the files where they are after download<br>
    &quot;rename&quot; - to add &quot;.downloaded&quot; to the end of every downloaded
    file<br>
    &quot;delete&quot; - to delete the file after downloading <br>
    This determines what happens to log files, after they have been download by
    the select(bf)-parser <br>
    through FTP. Please take care that the certain rights for this actions are allowed
    on the FTP-Server.<br>
    <br>
    -&gt; Set the &quot;delete-decompressed-xml-files&quot; to one of the following
    values: <br>
    &quot;true&quot; - for deleting files that were decompressed zxmls after processing
    <br>
    &quot;false&quot; - for leaving them where they are after processing<br>
    <br>
    -&gt; Set the &quot;log-bots&quot; to one of the following values: <br>
    &quot;true&quot; - for accounting bots to your stats<br>
    BE AWARE: select(bf) will only log bots if they have &quot;createPlayer&quot;
    events<br>
    in the log files, which is not the case at the moment (at least in<br>
    vanilla BF1942). This little restriction came with the tracking down<br>
    of changing Player-IDs during a round.<br>
    &quot;false&quot; - for leaving them out</p>
<p> -&gt; Set the &quot;trim-database&quot; values<br>
    You can set the value between the &lt;trim-database&gt;-tags to &quot;true&quot;
    if you want the datbase to be<br>
    trimmed at the end of parsing. By default, only outdated games will be removed<br>
    &quot;days&quot; - you should set the &lt;trim-database&gt;-tag attribute to
    the number of days you want to keep in the database<br>
    &quot;keep-players&quot; - this value can either be &quot;true&quot; or &quot;false&quot;<br>
    You should set it to &quot;true&quot; if you want to keep players even if they
    haven't<br>
    played on the server within the given time. Otherwise they are also removed.<br>
    BE AWARE: If you plan to keep you database trimmed to a certain amount of days,
    I URGENTLY<br>
    advise you to reset your stats first. Also choose a very small interval of adding
    new logs to<br>
    your database. You have to know: deleting events from the database can be as
    time-consuming<br>
    as selecting data from it. So if you have a large amount of games in there that
    are outdated<br>
    it will take a certain amount of time to trim them down. If you REALLY have
    a HUGE amount<br>
    of outdated games, this trimming procedure will take a HUGE amount of time!!<br>
    <br>
    -&gt; Set the &quot;rename-at-error&quot; to one of the following values: <br>
    &quot;true&quot; - for renaming files that showed errors to &quot;*.error&quot;<br>
    &quot;false&quot; - for don't doing that<br>
    <br>
    -&gt; Set the &quot;consistency-check&quot; to one of the following values:<br>
    &quot;true&quot; - for enabling the consistency checker<br>
    &quot;false&quot; - for disabling<br>
    BE AWARE: The consistency checker might change your XML-files. It checks the
    files for<br>
    probable inconsistency in the XML-structure. Corrections/Changes made to the
    log file<br>
    are marked with a comment so that you can find them in case something goes wrong.<br>
    Also the consistency-check takes a bit more time than simple parsing.<br>
    <br>
    -&gt; Set the &quot;memory-safer&quot; to one of the following values:<br>
    &quot;true&quot; - for enabling the memory safer<br>
    &quot;false&quot; - for disabling<br>
    BE AWARE: The memory safer clears up unused memory after every file parsed.
    Therefore<br>
    the complete parsing process might take longer.<br>
    <br>
    -&gt; Adjust the &lt;log&gt; section of the XML-file<br>
    <br>
    <br>
    [Configuring directories]<br>
    You can open a &lt;dir&gt; tag for every directory that contains log files and
    that you want the parser to go<br>
    through. The examples should lead you the way. Also you can set the special
    attribute &quot;live&quot; of the<br>
    dirs to one of the following values:<br>
    &quot;true&quot; - for considering this dir as a live-server dir (this will
    always leave out <br>
    the most actual file in the dir because this is probably the file under<br>
    BF1942 server-access)<br>
    BE AWARE: Don't place any newer files in the dir cause in that case this file<br>
    will be considered most actual. There is no better solution for this because<br>
    under Windows the log under server-access is not locked in any way <br>
    &quot;false&quot; - for not doing the above described <br>
    Between the &lt;dir&gt; and &lt;/dir&gt; tag, please write the name of the directory
    that holds the log files.<br>
    NOTICE: Only use ABSOLUTE dir names. In any other case select(bf) will not be
    able to find them.<br>
    <br>
    [Configuring FTP] <br>
    You can also add a &lt;ftp&gt; tag for every FTP-location you want to gather
    logs from. Please take care<br>
    that you also set the following values for the &lt;ftp&gt;-tag:<br>
    &quot;host&quot; - containg either the DNS-name of the FTP-server that you want
    to get the files from <br>
    or a valid IP-address<br>
    &quot;user&quot; - the FTP-user you'd like to use, to access the logs<br>
    &quot;password&quot; - the password for this FTP-user<br>
    &quot;live&quot; - please see the &lt;dir&gt;-part for the explanation of this
    value<br>
    Between the &lt;ftp&gt; and &lt;/ftp&gt; tag, please write the directory on
    the FTP-server that holds the log-files.<br>
    Please consider, that you'll have to use a directory-path that originates from
    the directory that the<br>
    user you configured starts in. For example if you use a user who starts in &quot;c:\program
    files\battlefield server\&quot; <br>
    and your logs files reside in &quot;c:\program files\battlefield server\mods\bf1942\logs&quot;
    the tags would look like this<br>
    &lt;ftp ...&gt;mods/bf1942/logs&lt;/ftp.<br>
    NOTICE: If the user you use already starts in the right directory, simply write
    nothing between the tags (&lt;ftp ...&gt;&lt;/ftp&gt;)<br>
</p>
<p><br>
    Now you need to adjust one php-file so that the viewing pages can find the database</p>
<p>- Go to the &quot;php/include&quot;-directory and open &quot;sql.php&quot;<br>
    And adjust ONLY the following values:<br>
    $SQL_host = &quot;&lt;host's address&gt;&quot;;<br>
    $SQL_user = &quot;&lt;user name&gt;&quot;;<br>
    $SQL_datenbank = &quot;&lt;database name&gt;&quot;;<br>
    $SQL_password = &quot;&lt;database password&gt;&quot;;<br>
    <br>
    <br>
    III. Step Three<br>
    Shoot the PHP-Files to the desired dir of your webserver. </p>
<p>After that open your browser and goto http://your-selectbf-website/_setup.php!</p>
<p>Now choose a desired password for the Admin-Mode.<br>
    Now push the &quot;Create Tables&quot; to create all the tables needed by select(bf).<br>
    PLEASE NOTE: If you already have a running 0.3 release running then you don't
    <br>
    have to do anything for the 0.3b release. Nothing has changed in the database
    there!</p>
<p>You can also push the &quot;Update Datamodell&quot;-button to added needed
    tables and columns for<br>
    0.3 to a 0.2c datamodell.<br>
    BUT BE AWARE: You of course kept you data then, BUT the stats will NOT show
    anything<br>
    until you ran the parser for another time. select(bf) 0.3 needs some cached
    data that<br>
    speed-up the whole page, which are only written by the new 0.3 parser. Also
    there<br>
    are new values from the logs that are monitored in 0.3. These CAN NOT be reconstructed<br>
    from your 0.2c data and therefore will not be available.</p>
<p>The &quot;Drop Tables&quot; Button is for deleting all select(bf) tables from
    your database.<br>
    NOTICE: During public access better remove the _setup.php from your webserver!</p>
<p><br>
    IV.Step Four<br>
    Run the parser. Therefore you can either use <br>
    <br>
    run-selectbf.bat: For full select(bf)-output to the console<br>
    run-selectbf-slient.bat: For running select(bf) silent and write the output
    to &quot;selectbf.out&quot;</p>
<p>For our Unix friends there are similar files. Simply look in the bin :)! </p>
<p>V.Step Five<br>
    Watch your stats at http://your-selectbf-website</p>
<p>=========================<br>
    Parser behaviour<br>
    =========================</p>
<p>I. File-extensions<br>
    The parser will determine the files to parser from their extensions. Meaning
    that he will try to parser<br>
    EVERY .xml in a log-dir for a simple BF1942-log and tries to decompress every
    .zxml-file first.<br>
    So please take care that you have every file properly named. This is the same
    for FTP. The parser will <br>
    only download files that are either named .xml or .zxml</p>
<p><br>
    II. Filenames<br>
    Please note that the Parser somehow depends on the original BF1942-filenames
    for you log-files. Meaning<br>
    that you better not change them, or you risk a non-conclusive outcome. The parser
    uses the original<br>
    filename to determine the start of the game that this log was written for. If
    this is not possible, meaning<br>
    that the filename is not BF-conform anymore, then the parser will take the Last-Modified
    Date of the<br>
    file as the game-starttime to at least get as close as possible to the original
    starttime.</p>
<p></p>
<p>=========================<br>
    For our UNIX-Friends<br>
    =========================</p>
<p>Even though select(bf) is developed under a pure Windows environment, all the
    technics used are <br>
    platform independed. Meaning that the Java-Parser is working without any new
    compilation for Linux<br>
    also. You'll just have to use the other set of batch-files.</p>
<p> selectbf.sh: For full select(bf)-output to the console<br>
    selectbfsilent.sh: For running select(bf) silent and write the output to &quot;selectbf.out&quot;</p>
<p>There should be no problems and/or differences in the outcome comparing to
    the Windows-usage. But still<br>
    you'll have to download a Linux-JRE for running Java, but you guessed that right
    ;-)?</p>
<p><br>
    =========================<br>
    Troubles<br>
    =========================</p>
<p>There are sometimes troubles with the data-structure or logic of some log-files.
    This is because<br>
    Battlefield is not very consistent with it's log writing. There are sometimes
    heavy XML-errors<br>
    or missing informations (missing informations should only appear in very small
    files). I can't<br>
    do something about that because the errors are made on BF-side and s(bf) skips
    those files to<br>
    keep the data consistent.<br>
    If you experience anything that doesn't look like it should be or you have any
    suggestions, <br>
    feel free to post them at http://www.selectbf.org/forum</p>
<p>=========================<br>
    Known Issues in 0.3/0.3b<br>
    =========================</p>
<p>There are a few issues in this version I'm not very happy with. I'll state
    them here because<br>
    I expect some of you to mention them later on, and you should know that I'm
    also not very pleased<br>
    with them the way they are:</p>
<p>- Speed of the map.php-page<br>
    This page is cached in no way and might become slow when a big amount of data
    is in the database.<br>
    I tried about 1GB of logs and the page took 6secs to load it's data-</p>
<p>- The trim-database functionality <br>
    This thing takes far to long on big amounts of data. Sorry, but I haven't found
    a real solution for<br>
    that until now. Perhaps with some intelligent changes in the datamodell, I can
    clear this up</p>
<p>- The data-modell and data-base size<br>
    The tablecount is at 25 now. Which is far enough for my taste, but for adding
    more speed to the<br>
    data I only have the idea to add even more tables. I think in the next version
    I'll give up the<br>
    &quot;save-every event&quot; thing an simply accumulate them into totals-tables.</p>
<p>- Data collection for server usage, character types etc.<br>
    Lots of more data are out there that I'd like to precollect and make available
    on the different<br>
    pages. But I'm somehow out of ideas, so I depend on you. Tell me what you'd
    like to see!</p>
<p>&nbsp; </p>

<hr noshade="noshade">
<p><a name="help" id="help"></a></p>
<h2>Help</h2>
<p>If you run into any problems with this script, please post it on the <a href="http://www.tswn.com/modules/newbb">forums</a>
    at TSWN.com or on the <a href="http://www.selectbf.org/forum/">forums</a> at
    the select(bf) site. I hope that this script serves you well and you enjoy it
    as much as I have.</p>

<p>&nbsp;</p></body>
</html>
