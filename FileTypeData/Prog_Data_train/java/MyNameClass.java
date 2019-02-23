public class MyNameClass {

	private String sFirstName;
	private String sLastName;

	public MyNameClass(){

	}

	public MyNameClass(String sFirst, String sLast){
		sFirstName = sFirst;
		sLastName = sLast;

	}

	public String getName(){
		return sFirstName.concat(" ").concat(sLastName);
	}


}