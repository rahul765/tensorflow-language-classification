package dojo.refactor.production.escaperule;

public class SimpleCharacterEscapeRule extends CharacterEscaperRule{

	public String escapeInvalidCharactersInXml(String s) {
		StringBuffer retval = new StringBuffer();
		char character;
		for (int i = 0; i < s.length(); i++) {
			character = s.charAt(i);
			if (characterShouldBeEscaped(character)) {
				retval.append(escapeCharacter((short)character));
			} else {
				retval.append((char) character);
			}
		}
		return retval.toString();
	}

	private boolean characterShouldBeEscaped(char character) {
		if (character > 128) return true;
		if ((character == 34
						|| character == 38 || character == 60 || character == 62)) {
			return true;
		}
		return false;
	}
}
