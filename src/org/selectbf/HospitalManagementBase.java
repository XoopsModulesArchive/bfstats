package org.selectbf;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Iterator;
import java.util.Vector;

import org.jdom.Element;



public class HospitalManagementBase
{
	private Vector good_deeds;
	private Vector pending_heals;
	
	private boolean persistent = false;
	
	public HospitalManagementBase()
	{
		good_deeds = new Vector();
		pending_heals = new Vector();
	}
	
	public void registerBeginHealEvent(HealEvent he)
	{
		if(he.isFinished())
		{
			good_deeds.add(he);
		}
		else
		{
			//first checked if player has any pending heals
			//seems to be a bug in the Logging system that not all heals are ended
			int player_id = he.getPlayer_id();
			for(Iterator i = pending_heals.iterator();i.hasNext();)
			{
				HealEvent localhe = (HealEvent) i.next();
				if(localhe.getPlayer_id()==player_id)
				{
					//any non correct heals become dropped
					i.remove();
				}
			}
			pending_heals.add(he);
		}
	}
	
	public void registerEndHealEvent(Element e) throws SelectBfException
	{
		boolean found = false;
		for(int i = 0; i<pending_heals.size() && !found;i++)
		{
			HealEvent he = (HealEvent) pending_heals.elementAt(i);
			if(he.addEndEvent(e))
			{
				found = true;
				pending_heals.remove(i);
				good_deeds.add(he);
			}
		}
	}
	
	public String toString()
	{
		String str = "";
		
		try
		{
			str += "---Finished heals---\n";
			for(Iterator i = good_deeds.iterator();i.hasNext();)
			{
				HealEvent he = (HealEvent) i.next();
				if(he.getType() == HealEvent.SELFHEAL)
				{
					str+= "PlayerId "+he.getPlayer_id()+" healed himself "+he.getAmount_healed()+" MedPack-Points took "+he.getHealtime()+" seconds (start: "+he.getTime()+") (end:"+he.getEndtime()+")\n";
				} else
				{
					str+= "PlayerId "+he.getPlayer_id()+" healed HealedPlayerId "+he.getHealed_player()+" "+he.getAmount_healed()+" MedPack-Points took "+he.getHealtime()+" seconds (start: "+he.getTime()+") (end:"+he.getEndtime()+")\n";
				}
			}
			
			str+= "---Pending Heals---\n";
			for(Iterator i = pending_heals.iterator();i.hasNext();)
			{
				HealEvent he = (HealEvent) i.next();
				if(he.getType() == HealEvent.SELFHEAL)
				{
					str+= "PlayerId "+he.getPlayer_id()+" started healing himself ("+he.getTime()+")\n";
				} else
				{
					str+= "PlayerId "+he.getPlayer_id()+" started healing HealedPlayerId "+he.getHealed_player()+" ("+he.getTime()+")\n";
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
			for(Iterator i = good_deeds.iterator(); i.hasNext();)
			{
				try
				{
					HealEvent he = (HealEvent) i.next();
					
					Player player = pmb.getPlayerForSlot(he.getPlayer_id(),he.getTime());
					Player healedplayer = pmb.getPlayerForSlot(he.getHealed_player(),he.getTime());
					
					PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_heals (player_id, healed_player_id, amount, healtime, starttime, endtime, round_id) VALUES (?,?,?,?,?,?,?)");
					ps.setInt(1,pmb.lookupDbId(player));
					ps.setInt(2,pmb.lookupDbId(healedplayer));
					ps.setInt(3,he.getAmount_healed());
					ps.setFloat(4,he.getHealtime());
					ps.setTimestamp(5,new Timestamp(he.getTime().getTime()));
					ps.setTimestamp(6,new Timestamp(he.getEndtime().getTime()));
					ps.setInt(7,roundId);
					ps.execute();				
					

				}
				catch(SelectBfException e)
				{
					if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
					{
						//do nothing, meaning don't register the heal
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
			throw new SelectBfException(SelectBfException.ALREADY_PERSISTENT,"HospitalManagementBase");
		}
	}

	public boolean isPersistent()
	{
		return persistent;
	}
	
	public int countHealsForPlayer(int healtype,int contextplayerid)
	{
		int count = 0;
		for(Iterator i = good_deeds.iterator();i.hasNext();)
		{
			HealEvent he = (HealEvent) i.next();
			
			if(he.getType() == healtype && he.getPlayer_id() == contextplayerid)
			{
				count++;						
			}
		}
		return count;
	}

}
