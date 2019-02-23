package com.jason.dto;

import java.util.Date;

public class KeywordHistory {
	private int khid;
	
	
	private Date searchTime;
	
	private Keyword keyword;
	
	
	
	public int getKhid() {
		return khid;
	}
	public void setKhid(int khid) {
		this.khid = khid;
	}
	
	
	public Date getSearchTime() {
		return searchTime;
	}
	public void setSearchTime(Date searchTime) {
		this.searchTime = searchTime;
	}
	public Keyword getKeyword() {
		return keyword;
	}
	public void setKeyword(Keyword keyword) {
		this.keyword = keyword;
	}
	@Override
	public String toString() {
		return "KeywordHistory [khid=" + khid  
				+ ", searchTime=" + searchTime+ ",( keyword=kid:" + keyword.getKid()+",text:"+keyword.getSearchWord()+",cnt:"+keyword.getCount()+ ")]";
	}
	
	
	
	
	
}
