package com.luxoft.bankapp.exceptions;

import com.luxoft.bankapp.model.Account;

public class NotEnoughtFundsException extends BankException {
	private String message;
	private float amount;
	
	public NotEnoughtFundsException() {
		this.message = "Not enought founds exception";
	}
	
	public NotEnoughtFundsException(Account accountToThrow, float amountToWithdrow) {
		message = "For account " + accountToThrow + " the founds are " + accountToThrow.getBalance() + 
				" so you can't withcdraw " + amountToWithdrow;
		this.amount = amount;
	}
	
	@Override
	public String getMessage() {
		return message;
	}
}
