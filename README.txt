------------------------------------------------------------------------------------------
select(bf) A Battelfield 1942 XML Log Parsing Tool and Statistics generator

Copyright (C) 2003  Tim Adler

Version 0.3b

http://www.selectbf.org
------------------------------------------------------------------------------------------
select(bf) is published under the conditions of the General Public License (GPL)
Therefore this program is distributed in the hope that it will be useful, but 
WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or 
FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

select(bf) makes use of some other projects that are believed to be be freely available. 
They are either packed with select(bf) as a library or integrated with the code.
There to mention: 

Connector/J available under GPL (http://www.mysql.com/)

JDOM available under Apache-style open source license (http://www.jdom.org/)

JZLIB available under GPL (http://www.jcraft.com/jzlib/)

Jakarta Commons/Net available under Apache Software License, Version 1.1 (http://jakarta.apache.org)

Please see further below for license informations regarding the above products!



Also included is a version of vLib Template (http://vlib.activefish.com).

------------------------------------------------------------------------------------------
NOTICE: No license harm is intended with select(bf) so, if you don't agree with 
how select(bf) does things, please contact tim@s-h-i-n-y.com. 
------------------------------------------------------------------------------------------

=========================
ABOUT SELECT(BF)
=========================

1.Foreword

In the first place Thanks for downloading and using select(bf). I hope it will be useful.
I started this little program of about three months ago. That time I had to set up a Battlefield
server for a LAN-Party and I wanted to have some Stats for the players to see. Unfortunately
I found nothing that came close to what I was searching for. So I decided to create a tool
of my own. Having features that people are used to from watching CS-statistic pages. The 
Battlefield Logs anyhow contain some more information, so that it is possible to even keep
track of specific games and round infos. But see yourself.


2.For your consideration

Please note: The Battlefield XML Log Format itself does not seem to be very bugfree. I had
lots of files during the testing that showed either flaws in the XML-structure or contained
errors on the <bf:event>-level so that an event is not always written the same.
I tried to make this little tool as tolerant as possible but still it sometimes rejects files 
because of flaws in the XML-Log itself. 

3.Thanks

First of all I'd like to thank all the guys that joined to select(bf) forums and gave their
cheers for this project. That's what makes the OpenSource-World go round :)!

On the software-writing side I had not much help, so I'd be glad for anybody to join the team.
Simply mail tim@s-h-i-n-y.com. I'm escpecially searching for somebody who knows PHPNuke, cause
lots of people would like a integration for that system.

Thanks to Andreas Fredriksson of DICE of the Battlefield development team who wrote the XML
Logging system and supplied some valuable answers on this one.

Thanks to everybody else that submitted errors and suggestions at http://www.selectbf.org.
Especially to the guys who modded s(bf) to fit their needs and some that wrote little code-bits
to help others making good use of this little thing.

Thanks to the creators of the three freesoftware-projects I used to create select(bf). Without
them making their software freely available this could never have been done.

=========================
INSTALL/USE INSTRUCTIONS
=========================

1.What you need to run this thing

PARSER
- Java Runtime Environment 1.4 or higher installed it is available freely at http://java.sun.com
  Be aware to download their offline-installations. The online-installations are somewhat creepy.
  You can also install the Java Software Development Kit 1.4 or higher it comes with the JRE.

WEBSERVER
- PHP 4.3.2 http://www.php.net
  NOTICE: This the version that select(bf) has been tested with. It will probably work with earlier
  versions also.

DATABASE
- MySQL 4.0.13 http://www.mysql.com
  NOTICE: This the version that select(bf) has been tested with. It will probably work with earlier
  versions also.


2.How you run this thing

I. Step One
First uncompress the contents of the archive you downloaded to a desired directory.

Archive structure:
bin - Contains the binaries of select(bf), the config-file and batch-files to run it.
lib - Contains the "connector/j", "jdom" and "commons-net" libraries needed to run the selectbf-parser.
php - Contains all the files that you later shoot into your webserver
src - Contains the source-files of the select(bf)-parser


II. Step Two
Create a database for the data that select(bf) collects, or gather choose one that you 
already have. You'll need the authentication and connect information. 

Adjusting the parser-config (whatever you do in here. Don't mess up the config-files XML structure
or the parser will not be able to read it)

- Open the "config.xml" file in the "bin"-directory. 

- Now you'll have to set the options you want in this file (its the config for the parser)
  
  ->	Set all database connection information to those of the database you want the parser to use.
  
  -> 	Set die "after-parsing" to one of the following values:
		  "remain" - for leaving the files where they are after parse
		  "rename" - to add ".parsed" to the end of every parsed file
		  "delete" - to delete the file after parsing

  -> 	Set die "after-download" to one of the following values:
		  "remain" - for leaving the files where they are after download
		  "rename" - to add ".downloaded" to the end of every downloaded file
		  "delete" - to delete the file after downloading		  
		This determines what happens to log files, after they have been download by the select(bf)-parser 
		through FTP. Please take care that the certain rights for this actions are allowed on the FTP-Server.
				  
  ->	Set the "delete-decompressed-xml-files" to one of the following values: 
          "true"   - for deleting files that were decompressed zxmls after processing 
          "false"  - for leaving them where they are after processing
          
  ->	Set the "log-bots" to one of the following values: 
          "true"   - for accounting bots to your stats
          		   BE AWARE: select(bf) will only log bots if they have "createPlayer" events
          		             in the log files, which is not the case at the moment (at least in
          		             vanilla BF1942). This little restriction came with the tracking down
          		             of changing Player-IDs during a round.
          "false"  - for leaving them out

  -> 	Set the "trim-database" values
    	You can set the value between the <trim-database>-tags to "true" if you want the datbase to be
    	trimmed at the end of parsing. By default, only outdated games will be removed
    	  "days"   		 - you should set the <trim-database>-tag attribute to the number of days you want to keep in the database
    	  "keep-players" - this value can either be "true" or "false"
    	                   You should set it to "true" if you want to keep players even if they haven't
    	                   played on the server within the given time. Otherwise they are also removed.
    	BE AWARE: If you plan to keep you database trimmed to a certain amount of days, I URGENTLY
    	advise you to reset your stats first. Also choose a very small interval of adding new logs to
    	your database. You have to know: deleting events from the database can be as time-consuming
    	as selecting data from it. So if you have a large amount of games in there that are outdated
		it will take a certain amount of time to trim them down. If you REALLY have a HUGE amount
		of outdated games, this trimming procedure will take a HUGE amount of time!!
          
  ->    Set the "rename-at-error" to one of the following values: 
  		  "true"   - for renaming files that showed errors to "*.error"
  		  "false"  - for don't doing that
 
  ->	Set the "consistency-check" to one of the following values:
  		  "true"   - for enabling the consistency checker
  		  "false"  - for disabling
  		BE AWARE: The consistency checker might change your XML-files. It checks the files for
  		probable inconsistency in the XML-structure. Corrections/Changes made to the log file
  		are marked with a comment so that you can find them in case something goes wrong.
  		Also the consistency-check takes a bit more time than simple parsing.
  		
  ->	Set the "memory-safer" to one of the following values:
    	  "true"   - for enabling the memory safer
    	  "false"  - for disabling
    	BE AWARE: The memory safer clears up unused memory after every file parsed. Therefore
    	the complete parsing process might take longer.
  		  
  -> 	Adjust the <log> section of the XML-file
  
  
  		[Configuring directories]
  		You can open a <dir> tag for every directory that contains log files and that you want the parser to go
  		through. The examples should lead you the way. Also you can set the special attribute "live" of the
  		dirs to one of the following values:
          "true"   - for considering this dir as a live-server dir (this will always leave out 
          			 the most actual file in the dir because this is probably the file under
          			 BF1942 server-access)
          		   BE AWARE: Don't place any newer files in the dir cause in that case this file
          			 		 will be considered most actual. There is no better solution for this because
          			 		 under Windows the log under server-access is not locked in any way 
          "false"  - for not doing the above described  		
        Between the <dir> and </dir> tag, please write the name of the directory that holds the log files.
  		NOTICE: Only use ABSOLUTE dir names. In any other case select(bf) will not be able to find them.
  		
  		[Configuring FTP]		
  		You can also add a <ftp> tag for every FTP-location you want to gather logs from. Please take care
  		that you also set the following values for the <ftp>-tag:
  		  "host"   		- containg either the DNS-name of the FTP-server that you want to get the files from 
  		  				  or a valid IP-address
  		  "user"   		- the FTP-user you'd like to use, to access the logs
  		  "password" 	- the password for this FTP-user
  		  "live"		- please see the <dir>-part for the explanation of this value
  		Between the <ftp> and </ftp> tag, please write the directory on the FTP-server that holds the log-files.
  		Please consider, that you'll have to use a directory-path that originates from the directory that the
  		user you configured starts in. For example if you use a user who starts in "c:\program files\battlefield server\" 
  		and your logs files reside in "c:\program files\battlefield server\mods\bf1942\logs" the tags would look like this
  		<ftp ...>mods/bf1942/logs</ftp.
  		NOTICE: If the user you use already starts in the right directory, simply write nothing between the tags (<ftp ...></ftp>)
  		

  
Now you need to adjust one php-file so that the viewing pages can find the database

- Go to the "php/include"-directory and open "sql.php"
  And adjust ONLY the following values:
	$SQL_host = "<host's address>";
	$SQL_user = "<user name>";
	$SQL_datenbank = "<database name>";
	$SQL_password = "<database password>";
  
  
III. Step Three
Shoot the PHP-Files to the desired dir of your webserver. 

After that open your browser and goto http://your-selectbf-website/_setup.php!

Now choose a desired password for the Admin-Mode.
Now push the "Create Tables" to create all the tables needed by select(bf).
PLEASE NOTE: If you already have a running 0.3 release running then you don't 
have to do anything for the 0.3b release. Nothing has changed in the database there!

You can also push the "Update Datamodell"-button to added needed tables and columns for
0.3 to a 0.2c datamodell.
BUT BE AWARE: You of course kept you data then, BUT the stats will NOT show anything
until you ran the parser for another time. select(bf) 0.3 needs some cached data that
speed-up the whole page, which are only written by the new 0.3 parser. Also there
are new values from the logs that are monitored in 0.3. These CAN NOT be reconstructed
from your 0.2c data and therefore will not be available.

The "Drop Tables" Button is for deleting all select(bf) tables from your database.
NOTICE: During public access better remove the _setup.php from your webserver!


IV.Step Four
Run the parser. Therefore you can either use 
  
	run-selectbf.bat: 			For full select(bf)-output to the console
	run-selectbf-slient.bat: 	For running select(bf) silent and write the output to "selectbf.out"

For our Unix friends there are similar files. Simply look in the bin :)!	

V.Step Five
Watch your stats at http://your-selectbf-website

=========================
Parser behaviour
=========================

I. File-extensions
The parser will determine the files to parser from their extensions. Meaning that he will try to parser
EVERY .xml in a log-dir for a simple BF1942-log and tries to decompress every .zxml-file first.
So please take care that you have every file properly named. This is the same for FTP. The parser will 
only download files that are either named .xml or .zxml


II. Filenames
Please note that the Parser somehow depends on the original BF1942-filenames for you log-files. Meaning
that you better not change them, or you risk a non-conclusive outcome. The parser uses the original
filename to determine the start of the game that this log was written for. If this is not possible, meaning
that the filename is not BF-conform anymore, then the parser will take the Last-Modified Date of the
file as the game-starttime to at least get as close as possible to the original starttime.



=========================
For our UNIX-Friends
=========================

Even though select(bf) is developed under a pure Windows environment, all the technics used are 
platform independed. Meaning that the Java-Parser is working without any new compilation for Linux
also. You'll just have to use the other set of batch-files.

	selectbf.sh:			For full select(bf)-output to the console
	selectbfsilent.sh:		For running select(bf) silent and write the output to "selectbf.out"

There should be no problems and/or differences in the outcome comparing to the Windows-usage. But still
you'll have to download a Linux-JRE for running Java, but you guessed that right ;-)?


=========================
Troubles
=========================

There are sometimes troubles with the data-structure or logic of some log-files. This is because
Battlefield is not very consistent with it's log writing. There are sometimes heavy XML-errors
or missing informations (missing informations should only appear in very small files). I can't
do something about that because the errors are made on BF-side and s(bf) skips those files to
keep the data consistent.
If you experience anything that doesn't look like it should be or you have any suggestions, 
feel free to post them at http://www.selectbf.org/forum

=========================
Known Issues in 0.3/0.3b
=========================

There are a few issues in this version I'm not very happy with. I'll state them here because
I expect some of you to mention them later on, and you should know that I'm also not very pleased
with them the way they are:

- Speed of the map.php-page
This page is cached in no way and might become slow when a big amount of data is in the database.
I tried about 1GB of logs and the page took 6secs to load it's data-

- The trim-database functionality 
This thing takes far to long on big amounts of data. Sorry, but I haven't found a real solution for
that until now. Perhaps with some intelligent changes in the datamodell, I can clear this up

- The data-modell and data-base size
The tablecount is at 25 now. Which is far enough for my taste, but for adding more speed to the
data I only have the idea to add even more tables. I think in the next version I'll give up the
"save-every event" thing an simply accumulate them into totals-tables.

- Data collection for server usage, character types etc.
Lots of more data are out there that I'd like to precollect and make available on the different
pages. But I'm somehow out of ideas, so I depend on you. Tell me what you'd like to see!


=========================
Plans for a 0.4 release
=========================

- Recognition of Players by their CD-KEY hashes
- Massively decrease database-usage

But this release won't happen before December, cause I have lots of RL-stuff to do ATM!

=========================
Appendix
=========================
------------------------------------------------------------------------------------------
JDOM License Additions:
 
 JDOM (http://www.jdom.org) is
 Copyright (C) 2000-2003 Jason Hunter & Brett McLaughlin.
 All rights reserved.
 
  THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED.  IN NO EVENT SHALL THE JDOM AUTHORS OR THE PROJECT
 CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 SUCH DAMAGE.

 This software consists of voluntary contributions made by many 
 individuals on behalf of the JDOM Project and was originally 
 created by Jason Hunter <jhunter AT jdom DOT org> and
 Brett McLaughlin <brett AT jdom DOT org>.  For more information on
 the JDOM Project, please see <http://www.jdom.org/>.
 
 
JZLIB License Additions:
 
 JZLIB (http://www.jcraft.com/jzlib/) is
 Copyright (c) 2000,2001,2002,2003 ymnk, JCraft,Inc. 
 All rights reserved.
 
  THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED WARRANTIES,
 INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL JCRAFT,
 INC. OR ANY CONTRIBUTORS TO THIS SOFTWARE BE LIABLE FOR ANY DIRECT, INDIRECT,
 INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA,
 OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


Connector/J License Additions:
 
 Copyright (C) 2002 MySQL AB

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 
 
Jakarta Commons/Net License Additions:
 
 Copyright (c) 2001 The Apache Software Foundation.  All rights reserved.
  
  1. Redistributions of source code must retain the above copyright
     notice, this list of conditions and the following disclaimer.
 
  2. Redistributions in binary form must reproduce the above copyright
     notice, this list of conditions and the following disclaimer in
     the documentation and/or other materials provided with the
     distribution.
 
  3. The end-user documentation included with the redistribution,
     if any, must include the following acknowledgment:
        "This product includes software developed by the
         Apache Software Foundation (http://www.apache.org/)."
     Alternately, this acknowledgment may appear in the software itself,
     if and wherever such third-party acknowledgments normally appear.
 
  4. The names "Apache" and "Apache Software Foundation" and
     "Apache Commons" must not be used to endorse or promote products
     derived from this software without prior written permission. For
     written permission, please contact apache@apache.org.
 
  5. Products derived from this software may not be called "Apache",
     nor may "Apache" appear in their name, without
     prior written permission of the Apache Software Foundation.
       
   THIS SOFTWARE IS PROVIDED ``AS IS'' AND ANY EXPRESSED OR IMPLIED
 WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES
 OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 DISCLAIMED.  IN NO EVENT SHALL THE APACHE SOFTWARE FOUNDATION OR
 ITS CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF
 USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT
 OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 SUCH DAMAGE.
------------------------------------------------------------------------------------------