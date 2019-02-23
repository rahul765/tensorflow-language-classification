package com.mailchimpclient.operations;

import com.mailchimpclient.MailchimpClient;

public abstract class MailchimpOperations {
	
	private final MailchimpClient client;

	public MailchimpOperations(MailchimpClient client) {
		this.client = client;
	}
	
	public MailchimpClient getMailchimpClient() {
		return client;
	}

}
