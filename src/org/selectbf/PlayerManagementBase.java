package org.selectbf;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Vector;

import org.jdom.Element;
import org.jdom.Namespace;

public class PlayerManagementBase extends SelectBfClassBase
{

	private Vector playerslots;
	private GameContext gc;	
	
	private HashMap dbIdLookupContext;
	
	boolean persistent = false;

	boolean acceptBots = false;
	
	public PlayerManagementBase(boolean acceptBots,GameContext gc, Namespace ns)
	{
		super(ns);

		this.acceptBots = acceptBots;
		
		playerslots = new Vector();
		dbIdLookupContext = new HashMap();
		this.gc = gc;
	}
	
	public Player getPlayerForSlot(int slotId, Date time) throws SelectBfException
	{
		boolean found = false;
		
		PlayerSlot ps = null;
		for(Iterator i = playerslots.iterator(); i.hasNext() && !found;)
		{
			ps = (PlayerSlot) i.next();
			if(ps.getSlotId() == slotId)
			{
				found = true;
			}
		}
		if(!found)
		{
			throw new SelectBfException(SelectBfException.NO_PLAYERSLOT_FOR_ID);
		}
		else
		{
			return ps.wasAssignedTo(time);
		}
	}
	
	public void addPlayer(Element e) throws SelectBfException
	{
		String type = e.getAttributeValue("name");
		
		if(type.equals("createPlayer"))
		{
			try
			{
				String playername = Event.valueFromParameters(e,"name",NAMESPACE);
				int contextId = Integer.parseInt(Event.valueFromParameters(e,"player_id",NAMESPACE));
				int is_ai = Integer.parseInt(Event.valueFromParameters(e,"is_ai",NAMESPACE));
				
				Player p = new Player(playername,contextId);
				
				//is there already a playerslot for that Id ?
				boolean found = false;
				for(Iterator i = playerslots.iterator(); i.hasNext() && !found;)
				{
					PlayerSlot ps = (PlayerSlot) i.next();
					if(ps.getSlotId() == contextId)
					{
						if((acceptBots && is_ai==1) || (is_ai == 0))
						{
							ps.registerReAssignment(p,gc.calcTimeFromDiffString(e.getAttributeValue("timestamp")));
							found = true;
						}
						
					}
				}
				if(!found)
				{
					playerslots.add(new PlayerSlot(contextId,p,gc.calcTimeFromDiffString(e.getAttributeValue("timestamp"))));								
				}
			}
			catch(NumberFormatException ne)
			{
				throw new SelectBfException(SelectBfException.DATA_DONT_MEET_EXPECTATIONS);
			}
		}
		else
		{
			throw new SelectBfException(SelectBfException.XML_DATA_NOT_VALID,"Needed event 'createPlayer' got "+type);
		}	
	}
	
	public void disconnectPlayer(Element e) throws SelectBfException
	{
		String type = e.getAttributeValue("name");
			
		if(type.equals("disconnectPlayer"))
		{	
			try
			{
				int contextId = Integer.parseInt(Event.valueFromParameters(e,"player_id",NAMESPACE));
	
				//is there already a playerslot for that Id ?
				boolean found = false;
				for(Iterator i = playerslots.iterator(); i.hasNext() && !found;)
				{
					PlayerSlot ps = (PlayerSlot) i.next();
					if(ps.getSlotId() == contextId)
					{
						ps.registerUnAssignment(gc.calcTimeFromDiffString(e.getAttributeValue("timestamp")));
						found = true;					
					}
				}
				if(!found)
				{
					throw new SelectBfException(SelectBfException.NO_PLAYERSLOT_FOR_ID);								
				}	
			}
			catch(NumberFormatException ne)
			{
				throw new SelectBfException(SelectBfException.DATA_DONT_MEET_EXPECTATIONS);
			}		
		}
		else
		{
			throw new SelectBfException(SelectBfException.XML_DATA_NOT_VALID,"Needed event 'disconnectPlayer' got "+type);
		}	
	}
	

	
	public String toString()
	{
		return playerslots.toString();
	}
	
	public int lookupDbId(Player p) throws SelectBfException
	{
		if(!persistent)
		{
			throw new SelectBfException(SelectBfException.PLAYERINFOS_ARE_NOT_PERSISTENT);
		}
		Integer i = (Integer) dbIdLookupContext.get(""+p.hashCode());
		return i.intValue();
	}

	public void closeAllSlots(Date time) throws SelectBfException
	{
		for(Iterator i = playerslots.iterator(); i.hasNext();)
		{
			PlayerSlot p = (PlayerSlot) i.next();	
			p.registerUnAssignment(time);		
		}
	}

	public void persist(DatabaseContext dc) throws SQLException, SelectBfException
	{
		//first collect all DISTINCT players from the slots
		Vector completePlayerList = new Vector();
		
		for(Iterator i = playerslots.iterator(); i.hasNext();)
		{
			PlayerSlot ps = (PlayerSlot) i.next();
			Vector playerslotplayers = ps.getDistinctPlayers();
			
			//now go through all players that have ever been in that slot
			for(Iterator j = playerslotplayers.iterator(); j.hasNext();)
			{
				Player p = (Player) j.next();
				
				boolean found = false;
				//and check if he/she is already in the complete list or not
				for(Iterator k = completePlayerList.iterator(); k.hasNext() && !found;)
				{
					Player p2 = (Player) k.next();
					if(p.equals(p2))
					{
						found = true;
					}
				}
				if(!found)
				{
					completePlayerList.add(p);
				}
			}
		}
		
		//now go through the whole list and persist the Player if needed
		for(Iterator i = completePlayerList.iterator(); i.hasNext();)
		{
			Player p = (Player) i.next();
			persistPlayer(p,dc);
		}

		persistent = true;
		
		//finally register the playtimes with the db
		persistsPlayerSlots(dc);
	}
	
	private void persistsPlayerSlots(DatabaseContext dc) throws SQLException, SelectBfException
	{
		//now write all the Playtimes to the database
		for(Iterator i = playerslots.iterator(); i.hasNext();)
		{
			PlayerSlot p = (PlayerSlot) i.next();
			p.persists(dc,this);
		}
	}
	
	private void persistPlayer(Player p, DatabaseContext dc) throws SQLException, SelectBfException
	{
		PreparedStatement pS = dc.prepareStatement("select id from selectbf_players where name=?");
		pS.setString(1,p.getName());
		ResultSet rs = pS.executeQuery();
			
		if(rs.next())
		{
			int dbId = rs.getInt(1);
			dbIdLookupContext.put(""+p.hashCode(),new Integer(dbId)); 													
		}
		else
		{
			PreparedStatement ps =  dc.prepareStatement("INSERT INTO selectbf_players (name, inserttime) VALUES (? ,now())");
			ps.setString(1,DatabaseContext.addSlashes(p.getName()));
			ps.execute();
			int dbId = dc.getLatestId(DatabaseContext.PLAYERS);
			dbIdLookupContext.put(""+p.hashCode(),new Integer(dbId));
		}
	}
}
