package pl.jojczykp.gumtree.address_book;

import java.util.stream.Collector;

public final class Question {

	private final String label;
	private final Collector<Record, ?, ?> collector;

	public Question(String label, Collector<Record, ?, ?> collector) {
		this.label = label;
		this.collector = collector;
	}

	public String getLabel() {
		return label;
	}

	public Collector<Record, ?, ?> getCollector() {
		return collector;
	}

}
