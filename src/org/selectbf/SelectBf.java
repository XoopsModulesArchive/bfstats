package org.selectbf;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.OutputStream;
import java.sql.Date;
import java.sql.SQLException;
import java.util.Calendar;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Vector;

import org.apache.commons.net.ftp.FTP;
import org.apache.commons.net.ftp.FTPClient;
import org.apache.commons.net.ftp.FTPFile;
import org.jdom.Document;
import org.jdom.Element;
import org.jdom.JDOMException;
import org.jdom.input.JDOMParseException;
import org.jdom.input.SAXBuilder;

import com.jcraft.jzlib.ZInputStream;

public class SelectBf
{
	private static SelectBfConfig CONFIG;

	public static void main(String[] args)
	{
		System.out.println("select(bf) 0.3b - A Battlefield XML Log File Parser");
		System.out.println("----------------------------------------------------");
		System.out.println("Copyright (C) 2003  Tim Adler");
		System.out.println("Published under GPL http://www.gnu.org/licenses/gpl.txt");
		System.out.println("This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY");
		System.out.println("without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.");
		System.out.println("Available at http://www.selectbf.org"); 
		System.out.println("----------------------------------------------------");
		
		try
		{
			SAXBuilder builder = new SAXBuilder();
			Vector dirs = new Vector();
			Vector ftps = new Vector();
			
			try
			{
				//read the configuration
				File config = new File("config.xml");
				if(!config.exists())
				{
					throw new SelectBfException(SelectBfException.CONFIG_FILE_MISSING);
				} 
	
				Document configDoc = builder.build(config);
				Element configRoot = configDoc.getRootElement();
				
				CONFIG = new SelectBfConfig(configRoot);
		
				//NOW register all the log sources
				Element logs = configRoot.getChild("logs");
				List logdirs = logs.getChildren("dir");
				
				for(Iterator i = logdirs.iterator(); i.hasNext();)
				{
					Element logdir = (Element) i.next();
					
					HashMap hash = new HashMap();
					hash.put("dir",logdir.getText());
					hash.put("live",logdir.getAttributeValue("live"));
					
					dirs.add(hash);
				}
				
				List ftpdirs = logs.getChildren("ftp");
				for(Iterator i = ftpdirs.iterator(); i.hasNext();)
				{
					Element ftpdir = (Element) i.next();
					
					HashMap hash = new HashMap();
					hash.put("host",ftpdir.getAttributeValue("host"));
					hash.put("user",ftpdir.getAttributeValue("user"));
					hash.put("password",ftpdir.getAttributeValue("password"));
					hash.put("live",ftpdir.getAttributeValue("live"));
					hash.put("dir",ftpdir.getText());
					
					ftps.add(hash);
				}
			}
			catch(JDOMParseException e)
			{
				throw new SelectBfException(SelectBfException.CONFIG_FILE_ERROR,e.toString());
			}
			
			//process normal directories
			for(Iterator ite = dirs.iterator(); ite.hasNext();)
			{			
				HashMap hash = (HashMap) ite.next();
				
				String targetdir = (String) hash.get("dir");
				
				//check if dir is declarated "live"
				String isLive = (String) hash.get("live");
				boolean live = false;
				if(isLive != null && isLive.equals("true")) live = true;
				
								
				File dir = new File(targetdir);
				
				if(!dir.exists()) 
				{
					System.out.println("There is no dir '"+targetdir+"' - SKIPPING");
				}
				else
				{
					System.out.println("Logs-Directory: "+dir.getAbsolutePath());
					logFilesInDir(dir, live);					
				}
			}
			
			//process FTP-Directories
			for(Iterator ite = ftps.iterator(); ite.hasNext();)
			{
				HashMap hash = (HashMap) ite.next();
				String machine = (String) hash.get("host");
				String user = (String) hash.get("user");
				String password = (String) hash.get("password");
				String dir = (String) hash.get("dir");
				
				//check if directory is declared live
				String isLive = (String) hash.get("live");
				boolean live = false;
				if(isLive != null && isLive.equals("true")) live = true;
				
				processFtpDir(machine,user,password,dir,live);
			}
			
			//trim the database if wanted
			if(CONFIG.isTrimDatabase())
			{
				System.out.println("-----------------TRIMMING DATABASE-----------------\nPlease wait this takes a while ");
				DatabaseTrimmer dbt = new DatabaseTrimmer(CONFIG);
				dbt.trim();
				System.out.println(dbt.toString());
				dbt.close();
			}
			
			//now precache the needed tables
			System.out.println("-----------------PRECACHING DATA-----------------\nPlease wait this takes a while ");
			DatabaseCacher dbc = new DatabaseCacher(CONFIG);
			dbc.cacheCharacterTypeUsage();
			dbc.cacheVehicleUsage();
			dbc.cacheWeaponKills();
			dbc.cachePlayerRanking();
			dbc.cacheMapStats();
			dbc.close();
	
		}
		catch(Exception e)
		{
			handleException(e);
		}
		System.out.println("---------------------------------------------------");
		System.out.println("Process FINISHED");
		System.out.println("Thx for using select(bf)!");
		System.out.println("---------------------------------------------------");
	}
	
	private static void processFtpDir(String machine, String user, String password, String dir,boolean live) throws SelectBfException
	{
		try
		{
			FTPClient ftp = new FTPClient();
			System.out.println("[FTP] Connecting to "+machine);
			ftp.connect(machine);
			if(ftp.login(user,password))
			{
				System.out.println("[FTP] LOGIN successful!");
				ftp.setFileType(FTP.BINARY_FILE_TYPE);
				
				if(ftp.changeWorkingDirectory(dir))
				{
					File _ftp_download = new File("_ftp_download");
					if(!_ftp_download.exists())
					{
						_ftp_download.mkdir();
					}
					
					FTPFile[] ftpfiles = ftp.listFiles();
					
					int files_to_process = 0;
					for(int i = 0; i<ftpfiles.length; i++)
					{
						FTPFile f = ftpfiles[i];
						if(f.getName().endsWith(".xml") || f.getName().endsWith(".zxml"))
						{						
							files_to_process++;
						}
					}
					
					int processed_files = 0;
					for(int i = 0; i<ftpfiles.length; i++)
					{
						FTPFile f = ftpfiles[i];
						
						if(!((i==(ftpfiles.length-1) && live)))
						{
							if(f.getName().endsWith(".xml") || f.getName().endsWith(".zxml"))
							{
								processed_files++;
								long size = f.getSize();
								System.out.print("[FTP] Downloading "+f.getName()+" ("+size+" Bytes) ("+processed_files+"/"+files_to_process+")");
								OutputStream output = new FileOutputStream("_ftp_download/"+f.getName());
								ftp.retrieveFile(f.getName(),output);
								
								if(CONFIG.getAfter_download().equals("rename"))
								{
									if(!ftp.rename(f.getName(),f.getName()+".downloaded"))
									{
										System.out.print(" COULDN'T RENAME");
									}
									
								} else 
								if(CONFIG.getAfter_download().equals("delete"))
								{
									if(!ftp.deleteFile(f.getName()))
									{
										System.out.print(" COULDN'T DELETE");
									}
								}
								System.out.println(" FINISHED");
							}
						}	
						else
						{
							System.out.println("[FTP] Skipping "+f.getName()+" because LIVE is set!");
						}			
					}
				
					System.out.println("[FTP] Closing connection");
					
					ftp.logout();
					ftp.disconnect();
					
					logFilesInDir(_ftp_download, false);
					
					System.out.println("[FTP] Cleaning _ftp_download !");
					String[] logs = _ftp_download.list();
					for(int i = 0; i<logs.length; i++)
					{
						String file = logs[i];
						if(file.endsWith(".xml") || file.endsWith(".zxml") || file.endsWith(".parsed") || file.endsWith(".error"))
						{
							new File("_ftp_download"+File.separator+file).delete();
						}
					}
				}
				else
				{
					System.out.println("[FTP] The Directory '"+dir+"' does not exist OR has no permission to access!");
					System.out.println("[FTP] Closing connection");
					ftp.logout();
					ftp.disconnect();
				}
			}
			else
			{
				System.out.println("[FTP] Couldn't login to the FTP-Location, please check your Login-Information!");
			}
						
		}
		catch(IOException io)
		{
			System.out.println("[FTP] Couldn't connect to "+machine+": "+io.toString());
		}		
	}


	private static void logFilesInDir(File dir, boolean live) throws SelectBfException
	{
		try
		{
			String[] dirlist = dir.list();
			
			//find out which file is newest and determine number of files that have to be processed
			int number_of_files = 0;
			int index_of_most_actual_file = -1;
			long newestTime = -1;
			for(int i = 0; i<dirlist.length; i++)
			{
				if(dirlist[i].endsWith(".zxml") || dirlist[i].endsWith(".xml"))
				{
					number_of_files++;
					File f = new File(dir.getAbsolutePath()+File.separatorChar+dirlist[i]);
					if(f.lastModified()>newestTime)
					{
						newestTime = f.lastModified();
						index_of_most_actual_file = i;
					}
				}
			}
						
			int log_file_number = 0;
			for(int i = 0; i<dirlist.length;i++)
			{
				String file_to_process = dir.getAbsolutePath()+File.separatorChar+dirlist[i];
				
				boolean delete_after_finish = false;
				boolean was_zxml = false;
				String org_filename = "";
				
				boolean isLiveFile = false;
				
				if(file_to_process.endsWith(".zxml"))
				{
					org_filename = file_to_process;
					file_to_process = decompressZXMLFile(file_to_process);
					delete_after_finish = true;
					was_zxml = true;
				}
					
				if(file_to_process.endsWith(".xml"))
				{
					File src = new File(file_to_process);
					
					log_file_number++;
						
					boolean errors = false;
					
					System.out.print("-> processing File '"+src.getName()+"' ("+log_file_number+"/"+number_of_files+")");
					
					try
					{
						if(live && i == index_of_most_actual_file)
						{
							isLiveFile = true;
							System.out.print(" NEWEST FILE - LIVE is set -> Probably under server-access - SKIPPING");
						}
						else
						{
							logFile(src);
							if(CONFIG.isMemorySafer())
							{
								System.gc();
							}
						}
					}
					catch(JDOMParseException e)
					{
						System.out.print(" ERROR  Data-structure of "+src+" CORRUPT:"+e.toString()+" - SKIPPING");
						errors = true;
					}
					catch(JDOMException e)
					{
						System.out.print(" ERROR  Data-structure of "+src+" CORRUPT:"+e.toString()+" - SKIPPING");
						errors = true;
					}
					catch(CancelProcessException ce)
					{
						//This is when the Cancel is triggered by intention
						System.out.print(" ERROR "+ce.getMessage()+" SKIPPED");
						errors = true;
					}
					catch(SelectBfException se)
					{
						//This is when the Cancel is forced because of a logic issue
						System.out.print(" ERROR This file has LOGIC issues: "+se.getMessage()+" SKIPPED");
						errors = true;
					}
					
					//handle the decompressed files
					if(delete_after_finish && CONFIG.isDelete_decompressed_xml_files())
					{
						src.delete();
					}
					System.out.println(" FINISHED");

					if(!isLiveFile)
					{
						//handle the (Z)XML-files
						if(was_zxml)
						{
							file_to_process = org_filename;
						}
						
						if(!errors)
						{
							if(CONFIG.getAfter_parsing().equals("delete"))
							{
								File f = new File(file_to_process);
								f.delete();
							} else
							if(CONFIG.getAfter_parsing().equals("rename"))
							{
								File f = new File(file_to_process);
								f.renameTo(new File(file_to_process+".parsed"));
							}
						}
						else
						if(CONFIG.isRenameAtError())
						{
							File f = new File(file_to_process);
							f.renameTo(new File(file_to_process+".error"));					
						}
					}
				}
			}
		}
		catch(SQLException e)
		{
			//if there is a Database problem
			System.out.println(" PROBLEM with the database: "+e.toString()+" - Please check you config!");
		}
		catch(Exception e)
		{
			//This is when the Parser crashed hard with one file
			writeFatalErrorMsg(e);
		}	
		System.out.println("---DONE---");	
	}

	private static void logFile(File src) throws SelectBfException, SQLException, JDOMException, IOException
	{
		//first check for XML-inconsistencies if configured
		if(CONFIG.isConsistencyCheck())
		{
			DataConsistencyChecker.checkAndCorrect(src);			
		}
		
		//try to create starttime from filename
		long time = 0;
		try
		{
			String filename = src.getName();
								
			String[] buffer = filename.split("-");
			buffer = buffer[1].split("_");
								
			int year = Integer.parseInt(buffer[0].substring(0,4));
			int month = Integer.parseInt(buffer[0].substring(4,6));
			int day = Integer.parseInt(buffer[0].substring(6,8));
			int hours = Integer.parseInt(buffer[1].substring(0,2));
			int minutes = Integer.parseInt(buffer[1].substring(2,4));
								
			Calendar c = Calendar.getInstance();
			c.set(year,month-1,day,hours,minutes);
			time = c.getTimeInMillis();
		}
		catch(Exception e)
		{
			//as backup just use the "last modified"-date of the file
			time = src.lastModified();
		}
		
		//now log the file
		try
		{
			SAXBuilder builder = new SAXBuilder();
				
			Document doc = builder.build(src);
			
			//now create the logical Game-Data
			GameContext gc = new GameContext(new Date(time),doc.getRootElement(),CONFIG.isLogBots());
			
			//now log them to the database
			DatabaseContext dc = new DatabaseContext(CONFIG);
			gc.persist(dc);
			dc.close();
																																												
		}
		catch(IOException e)
		{
			throw new SelectBfException(e);
		}

				
	}



	private static void handleException(Exception e)
	{
		if(e instanceof SelectBfException)
		{
			SelectBfException sbe = (SelectBfException) e;
			if(sbe.getType() == SelectBfException.GENERIC)
			{
				writeFatalErrorMsg(e);
			}
			else
			{
				System.out.println("ERROR: "+e.toString());
				e.printStackTrace();
			}
		} 
		else
		{
			writeFatalErrorMsg(e);
		}		
	}
	
	private static void writeFatalErrorMsg(Exception e)
	{
		System.out.println("\n================================================");
		System.out.println("FATAL ERROR");
		System.out.println("------------------------------------------------");
		System.out.println("An unexpected circumstance prevented the Parser");
		System.out.println("from continuing his work.");
		System.out.println("Please report this error and a COPY-PASTE of the");
		System.out.println("following to selectbf@s-h-i-n-y.com OR BETTER");
		System.out.println("in the forums at http://www.selectbf.org, Thanks!");
		System.out.println(e.toString());
		System.out.println("StackTrace:");
		
		StackTraceElement[] ste = e.getStackTrace();
		for(int i = 0; i<ste.length; i++)
		{
			System.out.println(ste[i].toString());
		}
		
		System.out.println("If you want to help even more, also supply a");
		System.out.println("copy of the Log-File that triggered this error.");
		System.out.println("================================================");
	}


	private static String decompressZXMLFile(String filename)
	{
		System.out.print("Decompressing "+filename+" ");
		int blocksize = 8192;
		String zipname, source;
		
		zipname = filename;
		source = zipname.substring(0,zipname.length()-4)+"xml";

		try
		{
			ZInputStream zipin = new ZInputStream(new FileInputStream(zipname));

			byte buffer[] = new byte[blocksize];
			FileOutputStream out = new FileOutputStream(source);
			
			int length;
			while((length = zipin.read(buffer,0,blocksize))!=-1)
			{
				out.write(buffer,0,length);
			}
			out.close();
			zipin.close();
		}
		catch(IOException e)
		{
			System.out.println("ERROR: Couldn't decompress "+filename);
		}	
		
		return source;	
	}
}
