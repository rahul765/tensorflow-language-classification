package com.mailchimpclient.exception;

public class MailchimpClientException extends RuntimeException {

	private static final long serialVersionUID = 1L;
	
    public MailchimpClientException() {
        super();
    }

    public MailchimpClientException(String message) {
        super(message);
    }

}
