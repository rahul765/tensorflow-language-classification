public class TestStrConv {

	private String s1 = "Initial value of String s1";
	private StringBuffer s2 = (StringBuffer) s1;

	public TestStrConv(){

	}

	public String getIt(){
		return s1.concat(" ").concat(s2);
	}


}