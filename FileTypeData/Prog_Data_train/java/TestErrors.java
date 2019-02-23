package me.adamcameron.miscellany;

public class TestErrors {

	public static void throwsError() {
		throw new java.lang.Error("Error Message");
	}

	public static void throwsThrowableNonException() throws ThrowableNonException {
		throw new ThrowableNonException("ThrowableNonException Message");
	}

	public static String catchesThrowableNonException() {
		try {
			throw new ThrowableNonException("ThrowableNonException Message");
		} catch (ThrowableNonException e){
			return e.getMessage();
		}
	}

	public static String catchesError() {
		try {
			throw new java.lang.Error("Error Message");
		} catch (java.lang.Error e){
			return e.getMessage();
		}
	}

}