package Server;

/**
 * JSONErrorMessage.java
 * -----------------------------
 * Basic class to be instantiated then casted into JSON,
 * for use to return an error message
 * in place of data from the server.
 * 
 * @author martin
 */

public class JSONErrorMessage {
	public boolean error = true;
	public String error_message = "";
	
	public JSONErrorMessage(String _error_message){
		this.error_message = _error_message;
	}
}
