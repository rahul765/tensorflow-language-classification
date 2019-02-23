package com.igor.chapter_4;

import javax.persistence.TypedQuery;

import org.assertj.core.api.Assertions;
import org.junit.Test;

import com.igor.setup.DbTestClient;

public class ItemBidSummaryTest {

	private static final String PERSISTENCE_UNIT = "Chapter4";

	@Test
	public void shouldExecuteSubSelect() throws Exception {

		DbTestClient client = new DbTestClient(PERSISTENCE_UNIT);

		Item item1 = new Item();
		Item item2 = new Item();

		item1.setName("item-1");
		item2.setName("item-2");

		Bid bid1 = new Bid(item1, 101);
		Bid bid2 = new Bid(item1, 102);
		Bid bid3 = new Bid(item2, 201);

		client.persist(item1, item2, bid1, bid2, bid3);

		// Action
		client.executeTransaction(em -> {

			ItemBidSummary summaryItem1 = em.find(ItemBidSummary.class, item1.getId());
			// Executed as:
			// select * from (
			//      select ... from ... where ... group by ...
			// ) where ITEMID = ?
			Assertions.assertThat(summaryItem1).isNotNull();
			Assertions.assertThat(summaryItem1.getBidsCount()).isEqualTo(2);

			ItemBidSummary summaryItem2 = em.find(ItemBidSummary.class, item2.getId());
			Assertions.assertThat(summaryItem2).isNotNull();
			Assertions.assertThat(summaryItem2.getBidsCount()).isEqualTo(1);

		});

		client.close();
	}

	@Test
	public void shouldSynchronizeBeforeSubquery() throws Exception {

		DbTestClient client = new DbTestClient(PERSISTENCE_UNIT);

		Item item = new Item();
		item.setName("item");

		Bid bid1 = new Bid(item, 101);
		Bid bid2 = new Bid(item, 102);

		client.persist(item, bid1, bid2);

		// Action
		client.executeTransaction(em -> {

			// This will override @Synchronize 
			// em.setFlushMode(FlushModeType.COMMIT);

			Bid bid3 = new Bid(item, 103);
			em.persist(bid3);
			Assertions.assertThat(bid3.getId()).isNotNull();
			// Id is assigned, but not inserted

			// This call does not flush the bid
			// Hibernate doesn’t flush automatically before a find() operation
			// Only before a Query is executed, if necessary
			ItemBidSummary itemBidSummary1 = em.find(ItemBidSummary.class, item.getId());
			Assertions.assertThat(itemBidSummary1.getBidsCount()).isEqualTo(2);

			// Have to detach
			em.detach(itemBidSummary1);

			// Hibernate will synchronize the right tables before querying
			// Automatic flush before queries if synchronized tables are affected!
			// Without @Synchronize assertions fails
			String ql = "select ibs from ItemBidSummary ibs where ibs.itemId = :id";
			TypedQuery<ItemBidSummary> query = em.createQuery(ql, ItemBidSummary.class);
			ItemBidSummary itemBidSummary2 = query.setParameter("id", item.getId()).getSingleResult();
			Assertions.assertThat(itemBidSummary2).isNotNull();
			Assertions.assertThat(itemBidSummary2.getBidsCount()).isEqualTo(3);

		});

		client.close();
	}

}
