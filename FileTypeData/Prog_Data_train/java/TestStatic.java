class Statics {
	public static String s = "A static string";
	
	public static void setS(String s){
		Statics.s = s;
	}
}

class TestStatic {
	
	public static void main(String[] args){
		System.out.print("Initial value: ");
		System.out.println(Statics.s);
		
		Statics.setS("Updated Value");

		System.out.print("After update: ");
		System.out.println(Statics.s);
	}

}