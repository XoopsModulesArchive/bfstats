
package org.selectbf;

import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Iterator;
import java.util.Vector;


public class KitManagementBase
{
	private Vector kits;
	
	private boolean persistent = false;
	
	
	public KitManagementBase()
	{
		kits = new Vector();
	}
	
	public void addKitEvent(KitEvent ke)
	{
		kits.add(ke);
	}
	
	public String toString()
	{
		String str = "---Kits taken up---\n";
		
		for(Iterator i = kits.iterator(); i.hasNext();)
		{
			KitEvent ke = (KitEvent) i.next();
			str += "PlayerId "+ke.getPlayerid()+" took up a '"+ke.getKit()+"' at "+ke.getTime()+"\n";
		}
		return str;
	}
	
	public void persist(DatabaseContext dc, int roundId, PlayerManagementBase pmb) throws SelectBfException, SQLException
	{
		if(!persistent)
		{
			for(Iterator i = kits.iterator(); i.hasNext();)
			{
				try
				{
					KitEvent ke = (KitEvent) i.next();
					
					Player p = pmb.getPlayerForSlot(ke.getPlayerid(),ke.getTime());

					PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_kits (player_id, kit, time, round_id) VALUES (?,?,?,?)");
					ps.setInt(1,pmb.lookupDbId(p));
					ps.setString(2,ke.getKit());
					ps.setTimestamp(3,new Timestamp(ke.getTime().getTime()));
					ps.setInt(4,roundId);
					ps.execute();
				}
				catch(SelectBfException e)
				{
					if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
					{
						//do nothing, meaning don't register the pickup, probably is a bot
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
			throw new SelectBfException(SelectBfException.ALREADY_PERSISTENT,"KitManagementBase");
		}
	}
}
