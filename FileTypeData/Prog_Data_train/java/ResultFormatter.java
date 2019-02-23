package pl.jojczykp.gumtree.address_book;

import java.util.List;
import java.util.stream.Collectors;

public class ResultFormatter {

	public String format(List<Answer> answers) {
		return answers.stream()
				.map(answer -> answer.getLabel() + ": " + answer.getValue())
				.collect(Collectors.joining(System.lineSeparator(), "", System.lineSeparator()));
	}
}
