package com.luxoft.bankapp.command;

import com.luxoft.bankapp.exceptions.ClientExistsException;
import com.luxoft.bankapp.exceptions.ClientNotFoundException;
import com.luxoft.bankapp.exceptions.NotEnoughtFundsException;
import com.luxoft.bankapp.service.BankFeedService;

/**
 * Created by dvorak on 28.12.15.
 */
public class LoadFromFileComand implements Command{
    @Override
    public void execute() throws ClientNotFoundException, NotEnoughtFundsException, ClientExistsException {
        BankFeedService.setActiveBank(BankCommander.currentBank);
        BankFeedService.loadFeed("Data");
        System.out.println("Bank data loaded successfully ");
    }

    @Override
    public void printCommandInfo() {
        System.out.println("Load from file ");

    }
}
