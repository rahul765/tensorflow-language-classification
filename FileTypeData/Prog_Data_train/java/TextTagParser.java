package HTMLHandlerClasses;

/**
 * Created by Konrad on 2017-07-14.
 */

import Utils.ParserUtils.Parser;

public class TextTagParser implements Parser {
    public Object parse(String textRep, int pos) throws Exception {
        return TextTag.parseFromString(textRep, pos);
    }
}
