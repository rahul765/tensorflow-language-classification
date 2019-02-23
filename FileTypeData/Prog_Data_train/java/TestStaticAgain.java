class TestStaticAgain {

	public static String s = "A static string";

	private static void setS(String s){
		TestStaticAgain.s = s;
	}
	
	public static void main(String[] args){
		System.out.print("Initial value: ");
		System.out.println(TestStaticAgain.s);
		
		setS("Updated Value");

		System.out.print("After update: ");
		System.out.println(TestStaticAgain.s);
	}

}