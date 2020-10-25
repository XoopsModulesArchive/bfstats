package org.selectbf;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Iterator;
import java.util.Vector;


public class ScoreManagementBase
{
	private Vector tks;
	private Vector kills;
	private Vector deaths;
	private Vector selfkills;
	private Vector attacks;
	
	private boolean persistent = false;
	
	public ScoreManagementBase()
	{
		tks = new Vector();
		kills = new Vector();
		deaths = new Vector();
		selfkills = new Vector();
		attacks = new Vector();
	}
	
	public void addScoreEvent(ScoreEvent s)
	{
		 switch(s.getScoretype())
		 {
			case ScoreEvent.KILL:			kills.add(s);break;
			case ScoreEvent.ATTACK:			attacks.add(s);break;
			case ScoreEvent.DEATH:			selfkills.add(s);break;
			case ScoreEvent.DEATH_NO_MSG:	deaths.add(s);break;
			case ScoreEvent.TK:				tks.add(s);break;
		 }
	}
	
	public String toString()
	{
		String str = "";
		
		str += "---Kills---\n";
		for(Iterator i = kills.iterator();i.hasNext();)
		{
			ScoreEvent s = (ScoreEvent) i.next();
			str+= "PlayerId "+s.getPlayer_id()+" ("+s.getWeapon()+") VictimId "+s.getVictim_id()+" ("+s.getTime()+")\n";
		}
		str+="\n";
		
		str += "---Deaths---\n";
		for(Iterator i = deaths.iterator();i.hasNext();)
		{
			ScoreEvent s = (ScoreEvent) i.next();
			str+= "PlayerId "+s.getPlayer_id()+" died ("+s.getTime()+")\n";
		}
		str+="\n";
				
		str += "---TKs---\n";
		for(Iterator i = tks.iterator();i.hasNext();)
		{
			ScoreEvent s = (ScoreEvent) i.next();
			str+= "PlayerId "+s.getPlayer_id()+" TK("+s.getWeapon()+") VictimId "+s.getVictim_id()+" ("+s.getTime()+")\n";
		}
		str+="\n";

		str += "---Attacks---\n";
		for(Iterator i = attacks.iterator();i.hasNext();)
		{
			ScoreEvent s = (ScoreEvent) i.next();
			str+= "PlayerId "+s.getPlayer_id()+" took basepoint ("+s.getTime()+")\n";
		}
		str+="\n";

		str += "---SelfKills---\n";
		for(Iterator i = selfkills.iterator();i.hasNext();)
		{
			ScoreEvent s = (ScoreEvent) i.next();
			str+= "PlayerId "+s.getPlayer_id()+" killed self ("+s.getTime()+")\n";
		}
		
		return str;	
	}

	public void persist(DatabaseContext dc, int roundId, PlayerManagementBase pmb) throws SQLException, SelectBfException
	{
		if(persistent)
		{
			throw new SelectBfException("This ScoreManagementBase is already persistent. Persisting again would cause inconsistency!");
		}
		for(Iterator i = tks.iterator(); i.hasNext();)
		{
			ScoreEvent se = (ScoreEvent) i.next();
			
			try
			{
				Player player = pmb.getPlayerForSlot(se.getPlayer_id(),se.getTime());
				Player victim = pmb.getPlayerForSlot(se.getVictim_id(),se.getTime());
				
				PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_tks (player_id, weapon, victim_id, time, round_id) VALUES (?,?,?,?,?)");
				ps.setInt(1,pmb.lookupDbId(player));
				ps.setString(2,se.getWeapon());
				ps.setInt(3,pmb.lookupDbId(victim));
				ps.setTimestamp(4,new Timestamp(se.getTime().getTime()));
				ps.setInt(5,roundId);
				ps.execute();			
			}
			catch(SelectBfException e)
			{
				if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
				{
					//do nothing, meaning don't register the TK
				}
				else
				{
					throw e;
				}
			}
		}
		
		for(Iterator i = kills.iterator(); i.hasNext();)
		{
			ScoreEvent se = (ScoreEvent) i.next();
			try
			{		
				Player player = pmb.getPlayerForSlot(se.getPlayer_id(),se.getTime());
				Player victim = pmb.getPlayerForSlot(se.getVictim_id(),se.getTime());
					
				PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_kills (player_id, weapon, victim_id, time, round_id) VALUES (?,?,?,?,?)");
				ps.setInt(1,pmb.lookupDbId(player));
				ps.setString(2,se.getWeapon());
				ps.setInt(3,pmb.lookupDbId(victim));
				ps.setTimestamp(4,new Timestamp(se.getTime().getTime()));
				ps.setInt(5,roundId);
				ps.execute();		
			}
			catch(SelectBfException e)
			{
				if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
				{
					//do nothing, meaning don't register the Kill
				}
				else
				{
					throw e;
				}
			}	
		}
		
		for(Iterator i = selfkills.iterator(); i.hasNext();)
		{
			ScoreEvent se = (ScoreEvent) i.next();
			try
			{	
				Player p = pmb.getPlayerForSlot(se.getPlayer_id(),se.getTime());
				
				PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_selfkills (player_id, time,round_id) VALUES (?,?,?)");
				ps.setInt(1,pmb.lookupDbId(p));
				ps.setTimestamp(2,new Timestamp(se.getTime().getTime()));
				ps.setInt(3,roundId);
				ps.execute();		
			}
			catch(SelectBfException e)
			{
				if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
				{
					//do nothing, meaning don't register the selfkill
				}
				else
				{
					throw e;
				}
			}	
		}	
		
		for(Iterator i = deaths.iterator(); i.hasNext();)
		{
			ScoreEvent se = (ScoreEvent) i.next();
			try
			{	
				Player p = pmb.getPlayerForSlot(se.getPlayer_id(),se.getTime());
				
				PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_deaths (player_id, time, round_id) VALUES (?,?,?)");
				ps.setInt(1,pmb.lookupDbId(p));
				ps.setTimestamp(2,new Timestamp(se.getTime().getTime()));
				ps.setInt(3,roundId);
				ps.execute();		
			}
			catch(SelectBfException e)
			{
				if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
				{
					//do nothing, meaning don't register the death
				}
				else
				{
					throw e;
				}
			}	
		}
			
		for(Iterator i = attacks.iterator(); i.hasNext();)
		{
			ScoreEvent se = (ScoreEvent) i.next();
			try
			{	
				Player p = pmb.getPlayerForSlot(se.getPlayer_id(),se.getTime());		
				
				PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_attacks (player_id, time,round_id) VALUES (?,?,?)");
				ps.setInt(1,pmb.lookupDbId(p));
				ps.setTimestamp(2,new Timestamp(se.getTime().getTime()));
				ps.setInt(3,roundId);
				ps.execute();		
			}
			catch(SelectBfException e)
			{
				if(e.getType() == SelectBfException.NO_PLAYERSLOT_FOR_ID)
				{
					//do nothing, meaning don't register the attack
				}
				else
				{
					throw e;
				}
			}	
		}
		persistent = true;					
	}
	
}
