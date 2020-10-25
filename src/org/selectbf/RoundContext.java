package org.selectbf;

import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Iterator;
import java.util.List;

import org.jdom.Element;
import org.jdom.Namespace;

public class RoundContext extends SelectBfClassBase
{
	private GameContext gc;
	private Date starttime;
	private PlayerManagementBase pmb;
	private ScoreManagementBase smb;
	private HospitalManagementBase hmb;
	private PitStopManagementBase psmb;
	private RoundInfoManagementBase rimb;
	private PlayerStatsManagementBase plsmb;
	private HighwayManagementBase hwmb;
	private KitManagementBase kmb;

	public RoundContext(Namespace ns, Element e, GameContext context, PlayerManagementBase pmb) throws SelectBfException
	{
		super(ns);
		this.gc = context;
		
		//first check if this round is worth processing
		checkPreconditions(e);
		
		String timevalue = e.getAttributeValue("timestamp");
		this.starttime = gc.calcTimeFromDiffString(timevalue);
		
		this.pmb = pmb;
		smb = new ScoreManagementBase();
		hmb = new HospitalManagementBase();
		psmb = new PitStopManagementBase();
		rimb = new RoundInfoManagementBase(this,NAMESPACE);
		plsmb = new PlayerStatsManagementBase(this,NAMESPACE);
		hwmb = new HighwayManagementBase();
		kmb = new KitManagementBase();
		
		//start registering all valuable Events	
		EventProcessor ep = new EventProcessor(NAMESPACE,this,pmb,smb,hmb,psmb,rimb,hwmb,kmb);
		
		List events = e.getChildren("event",NAMESPACE);
		
		//This is Event is saved in this context to have it accessible when last Event is needed.
		//watch when ending round-infos
		Element event_element = null;
		for(Iterator i = events.iterator(); i.hasNext();)
		{
			event_element = (Element) i.next(); 
			ep.processEvent(event_element);
		}
		
		//now collect the end-of-game stats
		Element roundstats = e.getChild("roundstats",NAMESPACE);
		if(roundstats != null)
		{
			plsmb.collectPlayerStats(roundstats);
				
			//if map wasn't restarted this will finish it of
			if(!rimb.isEnded())
			{
				rimb.registerRoundEnd(roundstats);
			}
			
		}
		
		//if round still isn't finished, use last event to finish round
		if(!rimb.isEnded())
		{
			rimb.registerRoundEnd(event_element);
		}
		
			
		//System.out.println(pmb);
		//System.out.println(smb);
		//System.out.println(hmb);
		//System.out.println(psmb);
		//System.out.println(rimb);
		//System.out.println(hwmb);
		//System.out.println(plsmb);
		//System.out.println(kmb);
	}
	
	public Date calcTimeFromDiffString(String sec)
	{
		return gc.calcTimeFromDiffString(sec);
	}

	public void persist(DatabaseContext dc, int gameId) throws SQLException, SelectBfException
	{
		//first write the roundinfos
		PreparedStatement ps = dc.prepareStatement("INSERT INTO selectbf_rounds (start_tickets_team1, start_tickets_team2, starttime, end_tickets_team1, end_tickets_team2, endtime, endtype, winning_team, game_id) VALUES (?,?,?,?,?,?,?,?,?)");
		ps.setInt(1,rimb.getStart_tickets_team1());
		ps.setInt(2,rimb.getStart_tickets_team2());
		ps.setTimestamp(3,new Timestamp(rimb.getStarttime().getTime()));
		ps.setInt(4,rimb.getEnd_tickets_team1());
		ps.setInt(5,rimb.getEnd_tickets_team2());
		ps.setTimestamp(6,new Timestamp(rimb.getEndtime().getTime()));
		if(rimb.getEndtype() == RoundInfoManagementBase.REGULAR)
		{
			ps.setString(7,"REGULAR");
			ps.setInt(8,rimb.getWinning_team());
		}
		else if(rimb.getEndtype() == RoundInfoManagementBase.RESTART)
		{
			ps.setString(7,"RESTART");
			ps.setInt(8,-1);
		} else
		{
			ps.setString(7,"FORCED");
			ps.setInt(8,-1);			
		}
		ps.setInt(9,gameId);
		ps.execute();
		
		int roundId = dc.getLatestId(DatabaseContext.ROUNDS);
		
		//now persist all the other Stuff
		smb.persist(dc,roundId,pmb);
		hmb.persist(dc,roundId,pmb);
		psmb.persist(dc,roundId,pmb);
		plsmb.persist(dc,roundId,pmb,hmb,psmb);
		hwmb.persist(dc,roundId,pmb);
		kmb.persist(dc,roundId,pmb);
	}
	
	public int getEndtype() throws SelectBfException
	{
		return this.rimb.getEndtype();
	}
	
	public Date getEndtime() throws SelectBfException
	{
		return rimb.getEndtime();
	}
		
	
	private void checkPreconditions(Element e) throws CancelProcessException
	{
		if(!e.getName().equals("round")) throw new CancelProcessException("Process of Round CANCELED because no 'round' element was delivered!");
		if(e.getChildren("event",NAMESPACE).size() == 0) throw new CancelProcessException("Process of Round CANCELED because there are too few 'events' in this round!");
	}
}
