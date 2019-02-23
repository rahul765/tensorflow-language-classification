package com.mailchimpclient.request;

public abstract class MailchimpRequest<T> {

	private String apikey;
	
	public String getApikey() {
		return apikey;
	}
	
	public void setApikey(String apikey) {
		this.apikey = apikey;
	}
	
	abstract void loadRequest(T entity);

}
