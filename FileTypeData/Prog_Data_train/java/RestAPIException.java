package com.mailchimpclient.exception;

public class RestAPIException extends RuntimeException {

	private static final long serialVersionUID = 1L;

	public RestAPIException(String message) {
		super(message);
	}

}
