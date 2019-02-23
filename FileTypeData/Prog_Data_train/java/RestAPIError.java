package com.mailchimpclient.response;

public class RestAPIError {

	private final String status;
	private final String code;
	private final String name;
	private final String error;

	public RestAPIError(String status, String code, String name, String error) {
		this.status = status;
		this.code = code;
		this.name = name;
		this.error = error;
	}

	protected RestAPIError() {
		this.status = null;
		this.code = null;
		this.name = null;
		this.error = null;
	}

	public String getStatus() {
		return status;
	}

	public String getCode() {
		return code;
	}

	public String getName() {
		return name;
	}

	public String getError() {
		return error;
	}

	@Override
	public String toString() {
		return "RestAPIError [status=" + getStatus() + ", code=" + getCode() + ", name=" + getName() + ", error=" + getError() + "]";
	}

}
