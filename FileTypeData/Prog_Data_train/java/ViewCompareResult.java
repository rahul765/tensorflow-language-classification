package com.dbcompare.domain;

public class ViewCompareResult {

	private String viewName;
	
	private String compareMessage;
	
	private String status;

	public String getViewName() {
		return viewName;
	}

	public void setViewName(String viewName) {
		this.viewName = viewName;
	}

	public String getCompareMessage() {
		return compareMessage;
	}

	public void setCompareMessage(String compareMessage) {
		this.compareMessage = compareMessage;
	}

	public String getStatus() {
		return status;
	}

	public void setStatus(String status) {
		this.status = status;
	}
}
