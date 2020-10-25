package org.selectbf;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Iterator;
import java.util.Vector;

import org.jdom.Element;

public class PitStopManagementBase
{
	private Vector finished_repairs;
	private Vector pending_repairs;
	
	private boolean persistent = false;
	
	public PitStopManagementBase()
	{
		finished_repairs = new Vector();
		pending_repairs = new Vector();
	}
	
	public void registerBeginRepairEvent(RepairEvent re)
	{
		if(re.isFinished())
		{
			finished_repairs.add(re);
		}
		else
		{
			//first check if the player has any pending repairs
			//this was in the heals so is implemented here just to be sure
			int player_id = re.getPlayer_id();
			for(Iterator i = pending_repairs.iterator();i.hasNext();)
			{
				RepairEvent localre = (RepairEvent) i.next();
				if(localre.getPlayer_id()==player_id)
				{
					//any non correct repairs become dropped
					i.remove();
				}
			}
			pending_repairs.add(re);
		}
	}
	
	public void registerEndRepairEvent(Element e) throws SelectBfException
	{
		boolean found = false;
		for(int i = 0; i<pending_repairs.size() && !found;i++)
		{
			RepairEvent re = (RepairEvent) pending_repairs.elementAt(i);
			if(re.addEndEvent(e))
			{
				found = true;
				pending_repairs.remove(i);
				finished_repairs.add(re);
			}
		}
	}	
	
	public String toString()
	{
		String str = "";
		
		try
		{
			str += "---Finished Repairs---\n";
			for(Iterator i = finished_repairs.iterator();i.hasNext();)
			{
				RepairEvent re = (RepairEvent) i.next();
				if(re.getType() == RepairEvent.REPAIR)
				{
					str+= "PlayerId "+re.getPlayer_id()+" repaired a '"+re.getRepaired_vehicle()+"' with "+re.getAmount_repaired()+" RepairPoints took "+re.getRepairtime()+" seconds (start: "+re.getTime()+") (end:"+re.getEndtime()+")\n";
				} else
				{
					str+= "PlayerId "+re.getPlayer_id()+" repaired VehiclePlayerId "+re.getRepair_player()+"'s '"+re.getRepaired_vehicle()+"' with "+re.getAmount_repaired()+" RepairPoints took "+re.getRepairtime()+" (start: "+re.getTime()+") (end:"+re.getEndtime()+")\n";
				}
			}
			
			str+= "---Pending Repairs---\n";
			for(Iterator i = pending_repairs.iterator();i.hasNext();)
			{
				RepairEvent re = (RepairEvent) i.next();
				if(re.getType() == RepairEvent.REPAIR)
				{
					str+= "PlayerId "+re.getPlayer_id()+" started repairing a '"+re.getRepaired_vehicle()+"' ("+re.getTime()+")\n";
				} else
				{
					str+= "PlayerId "+re.getPlayer_id()+" started repairing HealedPlayerId "+re.getRepair_player()+"'s '"+re.getRepaired_vehicle()+"' ("+re.getTime()+")\n";
				}
				
			}
		}
		catch(SelectBfException se)
		{
			str+=se.toString();
		}
		return str;
	}

	public void persist(DatabaseContext dc, int roundId, PlayerManagementBase pmb) throws SQLException, SelectBfException
	{
		if(!persistent)
		{
			for(Iterator i = finished_repairs.iterator(); i.hasNext();)
			{
				try
				{
					RepairEvent re = (RepairEvent) i.next();
					
					Player player = pmb.getPlayerForSlot(re.getPlayer_id(),re.getTime());
					Player reppairplayer = pmb.getPlayerForSlot(re.getRepair_player(),re.getTime());
					
					PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_repairs (player_id, repair_player_id, vehicle, amount, repairtime, starttime, endtime, round_id) VALUES (?,?,?,?,?,?,?,?)");
					ps.setInt(1,pmb.lookupDbId(player));
					ps.setInt(2,pmb.lookupDbId(reppairplayer));
					ps.setString(3,re.getRepaired_vehicle());
					ps.setInt(4,re.getAmount_repaired());
					ps.setFloat(5,re.getRepairtime());
					ps.setTimestamp(6,new Timestamp(re.getTime().getTime()));
					ps.setTimestamp(7,new Timestamp(re.getEndtime().getTime()));
					ps.setInt(8,roundId);
					ps.execute();	
					
					
				}
				catch(SelectBfException e)
				{
					if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
					{
						//do nothing, meaning don't register the repair
					}
					else
					{
						throw e;
					}
				}			
			}
			persistent = true;
		}
		else
		{
			throw new SelectBfException(SelectBfException.ALREADY_PERSISTENT,"PitStopManagementBase");
		}
		
	}		

	public boolean isPersistent()
	{
		return persistent;
	}
	
	public int countRepairsForPlayer(int repairtype,int contextplayerid)
	{
		int count = 0;
		for(Iterator i = finished_repairs.iterator();i.hasNext();)
		{
			RepairEvent re = (RepairEvent) i.next();
			
			if(re.getType() == repairtype && re.getPlayer_id() == contextplayerid)
			{
				count++;						
			}
		}
		return count;
	}

}
