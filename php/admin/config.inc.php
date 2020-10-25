<?php

include "admin_header.php";
include "../../../../mainfile.php";
require XOOPS_ROOT_PATH . "/modules/bfstats/admin/configxml.php";

?>
<?php
function viewconfig()
{
    xoops_cp_header();
    OpenTable();

    //require XOOPS_ROOT_PATH."/modules/bfstats/admin/configxml.php";
    global $dbase_uname;
    echo $dbase_uname;
    ?>
    <html>
    <head>
        <title>Untitled Document</title>
        <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
    </head>

    <body>
    <form name='form1' method='post' action='config.inc.php?op=xoopsxmlConfigEditWrite'>
        <table width='80%' border='0' align='center' cellpadding='0' cellspacing='0'>
            <tr>
                <td colspan='4'>Database Management</td>
            </tr>
            <tr>
                <td width='29%'>&nbsp;</td>
                <td colspan='3'>echo '$dbase_uname' &nbsp;</td>
            </tr>
            <tr>
                <td>database user name</td>
                <td colspan='3'><input name='dbase_uname' type='text' id='dbase_uname' value=''></td>
            </tr>
            <tr>
                <td>database name</td>
                <td colspan='3'><input name='dbase_name' type='text' id='dbase_name'></td>
            </tr>
            <tr>
                <td>database password</td>
                <td colspan='3'><input name='dbase_password' type='password' id='dbase_password'></td>
            </tr>
            <tr>
                <td>server (default is localhost)</td>
                <td colspan='3'><input name='server_name' type='text' id='server_name' value='localhost'></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>File Management</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>what to do after parsing</td>
                <td colspan='3'><select name='parsing' id='select'>
                        <option value='delete' selected>Delete</option>
                        <option value='rename'>Rename</option>
                        <option value='remain'>Remain</option>
                    </select></td>
            </tr>
            <tr>
                <td>what to do after download</td>
                <td colspan='3'><select name='download' id='select2'>
                        <option value='delete' selected>Delete</option>
                        <option value='rename'>Rename</option>
                        <option value='remain'>Remain</option>
                    </select></td>
            </tr>
            <tr>
                <td>delete compressed zxml files</td>
                <td colspan='3'><select name='delete_comp' id='select3'>
                        <option value='true' selected>True</option>
                        <option value='false'>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>rename at error</td>
                <td colspan='3'><select name='error_rename' id='select4'>
                        <option value='true' selected>True</option>
                        <option value='false'>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='4'>Data Management</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>log bots</td>
                <td colspan='3'><select name='log_bots' id='log_bots'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>trim database</td>
                <td colspan='3'><select name='trim_database' id='trim_database'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>keep players</td>
                <td colspan='3'><select name='keep_players' id='keep_players'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>database days</td>
                <td colspan='3'><select name='database_days' id='database_days'>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5' selected>5</option>
                        <option value='6'>6</option>
                        <option value='7'>7</option>
                        <option value='8'>8</option>
                        <option value='9'>9</option>
                        <option value='10'>10</option>
                    </select></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='4'>Log Sources</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td align='right'>(1.)</td>
                <td><input name='source1' type='text' id='source1' size='50'></td>
                <td width='15%'>Directory Live</td>
                <td width='11%'><select name='dirlive1' id='dirlive1'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td align='right'>(2.)</td>
                <td><input name='source2' type='text' id='source2' size='50'></td>
                <td>Directory Live</td>
                <td><select name='dirlive2' id='dirlive2'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td align='right'>(3.)</td>
                <td><input name='source3' type='text' id='source3' size='50'></td>
                <td>Directory Live</td>
                <td><select name='dirlive3' id='dirlive3'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td align='right'>(4.)</td>
                <td><input name='source4' type='text' id='source4' size='50'></td>
                <td>Directory Live</td>
                <td><select name='dirlive4' id='dirlive4'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td align='right'>(5.)</td>
                <td><input name='source5' type='text' id='source5' size='50'></td>
                <td>Directory Live</td>
                <td><select name='dirlive5' id='dirlive5'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td align='right'>(6.)</td>
                <td><input name='source6' type='text' id='source6' size='50'></td>
                <td>Directory Live</td>
                <td><select name='dirlive6' id='dirlive6'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='4'>FTP Host</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td align='right' valign='top'>(1.)</td>
                <td width='45%' valign='top'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td><p>ftp host</p></td>
                            <td><input name='ftp_host1' type='text' id='ftp_host1'></td>
                        </tr>
                        <tr>
                            <td>username</td>
                            <td><input name='ftp_username1' type='text' id='ftp_username1'></td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td><input name='ftp_password1' type='password' id='ftp_password1'></td>
                        </tr>
                        <tr>
                            <td>live host?</td>
                            <td><select name='live_host' id='select8'>
                                    <option value='true'>True</option>
                                    <option value='false' selected>False</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
                <td colspan='2' rowspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td align='right' valign='top'>(2.)</td>
                <td valign='top'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td><p>ftp host</p></td>
                            <td><input name='ftp_host2' type='text' id='ftp_host2'></td>
                        </tr>
                        <tr>
                            <td>username</td>
                            <td><input name='ftp_username2' type='text' id='ftp_username2'></td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td><input name='ftp_password2' type='password' id='ftp_password2'></td>
                        </tr>
                        <tr>
                            <td>live host?</td>
                            <td><select name='live_host2' id='select9'>
                                    <option value='true'>True</option>
                                    <option value='false' selected>False</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align='right' valign='top'>(3.)</td>
                <td valign='top'>
                    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
                        <tr>
                            <td><p>ftp host</p></td>
                            <td><input name='ftp_host3' type='text' id='ftp_host3'></td>
                        </tr>
                        <tr>
                            <td>username</td>
                            <td><input name='ftp_username3' type='text' id='ftp_username3'></td>
                        </tr>
                        <tr>
                            <td>password</td>
                            <td><input name='ftp_password3' type='password' id='ftp_password3'></td>
                        </tr>
                        <tr>
                            <td>live host?</td>
                            <td><select name='live_host3' id='select7'>
                                    <option value='true'>True</option>
                                    <option value='false' selected>False</option>
                                </select></td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td align='right'>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='4'>Other Stuff</td>
            </tr>
            <tr>
                <td align='right'>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>consistency check</td>
                <td colspan='3'><select name='con_check' id='con_check'>
                        <option value='true'>True</option>
                        <option value='false' selected>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>memory safe</td>
                <td colspan='3'><select name='mem_safe' id='mem_safe'>
                        <option value='true' selected>True</option>
                        <option value='false'>False</option>
                    </select></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan='3'><input type='submit' name='Submit' value='Save Configuration'></td>
            </tr>
        </table>
    </form>
    </body>
    </html>

    <?php
    CloseTable();
    xoops_cp_footer();
}

?>

<?php
function xoopsxmlConfigEditWrite()
{
    global $_POST;
    chmod(XOOPS_ROOT_PATH . "/modules/bfstats/bin/config2.xml", 666);

    $dbase_uname    = $_POST['dbase_uname'];
    $dbase_name     = $_POST['dbase_name'];
    $dbase_password = $_POST['dbase_password'];
    $server_name    = $_POST['server_name'];

    $parsing     = $_POST['parsing'];
    $download    = $_POST['download'];
    $delete_comp = $_POST['delete_comp'];
    $rename_xml  = $_POST['error_rename'];

    $log_bots     = $_POST['log_bots'];
    $trim_db      = $_POST['trim_database'];
    $keep_players = $_POST['keep_players'];
    $db_days      = $_POST['database_days'];

    $source1 = stripslashes($_POST['source1']);
    $source2 = stripslashes($_POST['source2']);
    $source3 = stripslashes($_POST['source3']);
    $source4 = stripslashes($_POST['source4']);
    $source5 = stripslashes($_POST['source5']);
    $source6 = stripslashes($_POST['source6']);

    $dirlive1 = $_POST['dirlive1'];
    $dirlive2 = $_POST['dirlive2'];
    $dirlive3 = $_POST['dirlive3'];
    $dirlive4 = $_POST['dirlive4'];
    $dirlive5 = $_POST['dirlive5'];
    $dirlive6 = $_POST['dirlive6'];

    $ftphost1 = $_POST['ftp_host1'];
    $ftphost2 = $_POST['ftp_host2'];
    $ftphost3 = $_POST['ftp_host3'];

    $ftpusername1 = $_POST['ftp_username1'];
    $ftpusername2 = $_POST['ftp_username2'];
    $ftpusername3 = $_POST['ftp_username3'];

    $ftppassword1 = $_POST['ftp_password1'];
    $ftppassword2 = $_POST['ftp_password2'];
    $ftppassword3 = $_POST['ftp_password3'];

    $livehost1 = $_POST['live_host1'];
    $livehost2 = $_POST['live_host2'];
    $livehost3 = $_POST['live_host3'];

    $concheck = $_POST['con_check'];
    $memsafe  = $_POST['mem_safe'];

    $filename  = XOOPS_ROOT_PATH . "/modules/bfstats/bin/config2.xml";
    $filename2 = XOOPS_ROOT_PATH . "/modules/bfstats/php/admin/configxml.php";

    $file  = fopen($filename, "w");
    $file2 = fopen($filename2, "w");

    $content .= "";
    $content .= "\n";
    $content .= "<selectbf-config>\n";
    $content .= " <!--DATABASE CONFIGURATION-->\n";
    $content .= "<database user=\"$dbase_uname\" password=\"$dbase_password\" database=\"$dbase_name\" port=\"3306\">$server_name</database>\n";
    $content .= "\n";
    $content .= "<!--FILE MANAGEMENT-->\n";
    $content .= "<after-parsing>$parsing</after-parsing>\n";
    $content .= "<after-download>$rename_xml</after-download>\n";
    $content .= "<delete-decompressed-xml-files>$delete_comp</delete-decompressed-xml-files>\n";
    $content .= "<rename-at-error>$rename_xml</rename-at-error>\n";
    $content .= "\n";
    $content .= " <!--DATA MANAGEMENT-->\n";
    $content .= " <log-bots>$log_bots</log-bots>\n";
    $content .= " <trim-database days=\"$db_days\" keep-players=\"$keep_players\">$trim_db</trim-database>\n";
    $content .= "\n";
    $content .= " <!--OTHER STUFF-->\n";
    $content .= " <consistency-check>$con_check</consistency-check>\n";
    $content .= " <memory-safer>$mem_safe</memory-safer>\n";
    $content .= "\n";
    $content .= " <!--LOG SOURCES-->\n";
    $content .= " <logs>\n";
    $content .= "  <dir live=\"$dirlive1\">$source1</dir>\n";
    $content .= "  <dir live=\"$dirlive2\">$source2</dir>\n";
    $content .= "  <dir live=\"$dirlive3\">$source3</dir>\n";
    $content .= "  <dir live=\"$dirlive4\">$source4</dir>\n";
    $content .= "  <dir live=\"$dirlive5\">$source5</dir>\n";
    $content .= "  <dir live=\"$dirlive6\">$source6</dir>\n";
    $content .= "  <ftp host=\"$ftphost1\" user=\$ftpusername1 password=\"$ftppassword1\" live=\"$livehost1\">/mods/dc_extended/logs</ftp>\n";
    $content .= "  <ftp host=\"$ftphost2\" user=\$ftpusername2 password=\"$ftppassword2\" live=\"$livehost2\">/mods/DesertCombat/logs</ftp>\n";
    $content .= "  <ftp host=\"$ftphost3\" user=\$ftpusername3 password=\"$ftppassword3\" live=\"$livehost3\">/mods/DesertCombat/logs</ftp>\n";
    $content .= " </logs>\n";
    $content .= "</selectbf-config>\n";
    $content .= "\n";

    $content2 .= "";
    $content2 .= "<?php\n";
    $content2 .= "\n";
    $content2 .= "\$dbase_uname = \"$dbase_uname\";\n";
    $content2 .= "\$dbase_name = \"$dbase_name\";\n";
    $content2 .= "\$dbase_password = \"$dbase_password\";\n";
    $content2 .= "\$server_name = \"$server_name\";\n";
    $content2 .= "\n";
    $content2 .= "\$parsing = \"$parsing\";\n";
    $content2 .= "\$download = \"$download\";\n";
    $content2 .= "\$delete_comp = \"$delete_comp\";\n";
    $content2 .= "\$rename_xml = \"$rename_xml\";\n";
    $content2 .= "\n";
    $content2 .= "\$log_bots = \"$log_bots\";\n";
    $content2 .= "\$trim_db = \"$trim_db\";\n";
    $content2 .= "\$keep_players = \"$keep_players\";\n";
    $content2 .= "\$db_days = \"$db_days\";\n";
    $content2 .= "\n";
    $content2 .= "\$source1 = \"$source1\";\n";
    $content2 .= "\$source2 = \"$source2\";\n";
    $content2 .= "\$source3 = \"$source3\";\n";
    $content2 .= "\$source4 = \"$source4\";\n";
    $content2 .= "\$source5 = \"$source5\";\n";
    $content2 .= "\$source6 = \"$source6\";\n";
    $content2 .= "\n";
    $content2 .= "\$dirlive1 = \"$dirlive1\";\n";
    $content2 .= "\$dirlive2 = \"$dirlive2\";\n";
    $content2 .= "\$dirlive3 = \"$dirlive3\";\n";
    $content2 .= "\$dirlive4 = \"$dirlive4\";\n";
    $content2 .= "\$dirlive5 = \"$dirlive5\";\n";
    $content2 .= "\$dirlive6 = \"$dirlive6\";\n";
    $content2 .= "\n";
    $content2 .= "\$ftphost1 = \"$ftphost1\";\n";
    $content2 .= "\$ftphost2 = \"$ftphost2\";\n";
    $content2 .= "\$ftphost3 = \"$ftphost3\";\n";
    $content2 .= "\n";
    $content2 .= "\$ftpusername1 = \"$ftpusername1\";\n";
    $content2 .= "\$ftpusername2 = \"$ftpusername2\";\n";
    $content2 .= "\$ftpusername3 = \"$ftpusername3\";\n";
    $content2 .= "\n";
    $content2 .= "\$ftppassword1 = \"$ftppassword1\";\n";
    $content2 .= "\$ftppassword2 = \"$ftppassword2\";\n";
    $content2 .= "\$ftppassword3 = \"$ftppassword3\";\n";
    $content2 .= "\n";
    $content2 .= "\$livehost1 = \"$livehost1\";\n";
    $content2 .= "\$livehost2 = \"$livehost2\";\n";
    $content2 .= "\$livehost3 = \"$livehost3\";\n";
    $content2 .= "\n";
    $content2 .= "\$concheck = \"$concheck\";\n";
    $content2 .= "\$memsafe = \"$memsafe\";\n";
    $content2 .= "?>\n";

    fwrite($file, $content);
    fwrite($file2, $content2);
    fclose($file);
    fclose($file2);
    //chmod(XOOPS_ROOT_PATH."/mainfile_test.php",775);
    redirect_header("config.inc.php", 1, 'BFSTATS config.xml Successfully Edited');
}

function confirm()
{
    xoops_cp_header();
    xoops_confirm(['op' => 'xoopsxmlConfigEditWrite', 'id_art' => $id_art, 'ok' => 1], 'config.inc.php', "Are you sure you want to edit this file?");
    xoops_cp_footer();
}

switch ($op) {
    default:
        viewconfig();
        break;

    case "xoopsmainfileConfigEdit":
        if (xoopsfwrite()) {
            xoopsmainfileConfigEdit();
        }
        break;

    case "xoopsxmlConfigEditWrite":
        xoopsxmlConfigEditWrite();
        break;

    case "confirm":
        confirm();
        break;
}

?>
