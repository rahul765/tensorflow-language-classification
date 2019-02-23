package pl.allegro.promo.geecon2015.domain.transaction;

import java.util.UUID;

public interface TransactionRepository {
    
    UserTransactions transactionsOf(UUID userId);
    
}
