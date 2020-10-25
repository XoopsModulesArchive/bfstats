package org.selectbf;

import java.util.Vector;

class Player
{
	private int contextId;
	private String name;
	private Vector other_names;
	
	public Player(String name, int contextId)
	{
		this.contextId = contextId;
		this.name = name;
	}
	
	public void addName(String str)
	{
		other_names.add(str);
	}
	
	public int getContextId()
	{
		return contextId;
	}

	public String getName()
	{
		return name;
	}
	
	public String toString()
	{
		return "ContextID: "+contextId+" Playername: "+name;
	}
	
	public boolean equals(Player p)
	{
		return this.name.equals(p.getName());		
	}
	
	public int hashCode()
	{
		return this.name.hashCode();
	}

}
