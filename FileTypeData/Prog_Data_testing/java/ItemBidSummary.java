package com.igor.chapter_4;

import javax.persistence.Basic;
import javax.persistence.Entity;
import javax.persistence.Id;

import org.hibernate.annotations.Immutable;
import org.hibernate.annotations.Subselect;
import org.hibernate.annotations.Synchronize;

/**
 * When an instance of ItemBidSummary is loaded, Hibernate executes your custom
 * SQL SELECT as a subselect. SELECT * FROM (subselect) WHERE itemId = ?.
 */
@Entity
@Immutable
// This is plain SQL, with physical naming strategy applied
@Subselect(value = "SELECT i.igor_id as igor_itemId, i.igor_name as igor_name, " +
		"count(*) as igor_bidsCount FROM igor_item i LEFT OUTER JOIN igor_bid b ON i.igor_id = b.igor_ITEM_ID " +
		"GROUP BY i.igor_id, i.igor_name")

// Table names are case sensitive, Hibernate bug HHH-8430
// As in the database, with physical naming strategy applied (igor_item, igor_bid)
// Hibernate will then know it has to flush modifications of Item and Bid instances 
// before it executes a query against ItemBidSummary, but not applied to find()
@Synchronize({ "item", "bid" })
public class ItemBidSummary {

	@Id
	private Integer itemId;

	@Basic
	private String name;

	@Basic
	private int bidsCount;

	public ItemBidSummary() {
		// For Hibernate
	}

	public Integer getItemId() {
		return itemId;
	}

	public String getName() {
		return name;
	}

	public int getBidsCount() {
		return bidsCount;
	}

}
