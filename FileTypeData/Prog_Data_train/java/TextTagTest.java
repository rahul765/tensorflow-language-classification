package TestClasses.HTMLTestClasses;

import HTMLHandlerClasses.HTMLTextTags;
import HTMLHandlerClasses.TagAttribute;
import HTMLHandlerClasses.TextTag;
import javafx.util.Pair;
import junit.framework.TestCase;

import java.util.ArrayList;

/**
 * Created by Konrad on 2017-08-10.
 */
public class TextTagTest extends TestCase {

    public void testToString() throws Exception {
        TextTag tag = new TextTag(HTMLTextTags.P, "Test text");

        String expectedRepr = "<p>Test text</p>";

        assertEquals(expectedRepr, tag.toString());
    }

    public void testParseFromString() throws Exception {
        String input = "<p class=\"class\"> text </p>";

        ArrayList<TagAttribute> atts = new ArrayList<>();
        atts.add(new TagAttribute("class", "class"));
        TextTag correct = new TextTag(HTMLTextTags.P, atts, " text ");


        Pair<TextTag, Integer> parsed = TextTag.parseFromString(input, 0);
        assertEquals(correct.toString(), parsed.getKey().toString());
    }
}