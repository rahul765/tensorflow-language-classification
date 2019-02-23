package pl.jojczykp.gumtree.address_book;

import org.junit.Test;

import java.util.stream.Collector;
import java.util.stream.Collectors;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.sameInstance;

public class QuestionUnitTest {

	private static final String LABEL = "label";
	private static final Collector<Record, ?, ?> COLLECTOR = Collectors.toSet();

	@Test
	public void shouldGetGivenValues() {
		Question question = new Question(LABEL, COLLECTOR);

		assertThat(question.getLabel(), is(sameInstance(LABEL)));
		assertThat(question.getCollector(), is(sameInstance(COLLECTOR)));
	}

}
