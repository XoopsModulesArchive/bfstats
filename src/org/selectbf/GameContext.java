package org.selectbf;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Calendar;
import java.util.Iterator;
import java.util.List;
import java.util.Vector;

import org.jdom.Element;


public class GameContext extends SelectBfClassBase
{
	private Date starttime;
	private Vector rounds;
	private ServerInfoManagementBase simb;
	private PlayerManagementBase pmb;

	int cancelRoundsCount = 0;
	
	public GameContext(Date d,Element root, boolean logBots) throws SelectBfException
	{
		super(root.getNamespace());
		
		starttime = d;
		
		pmb = new PlayerManagementBase(logBots,this,NAMESPACE);
		
		rounds = new Vector();
				
		List roundXML = root.getChildren("round",NAMESPACE);

		int i = 0;
		for(Iterator it = roundXML.iterator(); it.hasNext();)
		{
			try
			{
				Element e = (Element) it.next();
				
				RoundContext rc = new RoundContext(NAMESPACE,e,this,pmb); 
				rounds.add(rc);
				
				if(i==0)
				{
					simb = new ServerInfoManagementBase(e.getChild("server",NAMESPACE),NAMESPACE);
				}
				i++;
			}
			catch(CancelProcessException ce)
			{
				cancelRoundsCount++;
			}
		}
		
		if(cancelRoundsCount==roundXML.size())
		{
			throw new CancelProcessException("The whole Game was canceled because no Round contained information worth noting.");
		}
	}
	
	public Date calcTimeFromDiffString(String sec)
	{
		double d = Double.parseDouble(sec);
		
		Calendar c  = Calendar.getInstance();
		c.setTime(starttime);
		
		c.add(Calendar.SECOND,((int) d));		
		return new Date(c.getTimeInMillis());
	}

	public Date getStarttime()
	{
		return starttime;
	}




	public ServerInfoManagementBase getSimb()
	{
		return simb;
	}
	
	public void persist(DatabaseContext dc) throws SQLException, SelectBfException
	{
		//first persist game-infos
		PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_games (servername, modid, mapid, map, game_mode, gametime, maxplayers, scorelimit, spawntime, soldierff, vehicleff, tkpunish, deathcamtype, starttime) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		ps.setString(1,simb.getServer_name());
		ps.setString(2,simb.getModid());
		ps.setString(3,simb.getMapid());
		ps.setString(4,simb.getMap());
		ps.setString(5,simb.getGame_mode());
		ps.setInt(6,simb.getGametime());	
		ps.setInt(7,simb.getMaxplayers());
		ps.setInt(8,simb.getScorelimit());
		ps.setInt(9,simb.getSpawntime());
		ps.setInt(10,simb.getSoldierff());
		ps.setInt(11,simb.getVehicleff());
		ps.setInt(12,simb.getTkpunish());
		ps.setInt(13,simb.getDeathcamtype());
		ps.setTimestamp(14,new Timestamp(getStarttime().getTime()));
		
		//ps.setString(14,dc.toAddableDateString(getStarttime()));
		ps.execute();

		//then get this games Database Id 
		int gameId = dc.getLatestId(DatabaseContext.GAMES);
		
		
		Date gameendtime = ((RoundContext) rounds.elementAt(rounds.size()-1)).getEndtime();
		pmb.closeAllSlots(gameendtime);	
		pmb.persist(dc);
		
		//now persist the rounds
		for(int i = 0; i<rounds.size(); i++)
		{
			try
			{
				((RoundContext)rounds.elementAt(i)).persist(dc,gameId);
			}
			catch(SelectBfException se)
			{
				if(se.getType() == SelectBfException.ROUND_NOT_STARTED || se.getType() == SelectBfException.ROUND_NOT_ENDED)
				{
					//that means skip this round
				}
			}
		}
	}

	public Vector getRounds()
	{
		return rounds;
	}

}
