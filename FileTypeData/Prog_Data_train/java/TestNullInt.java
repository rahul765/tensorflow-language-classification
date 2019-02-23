public class TestNullInt {
	public static void main(String[] args){
		System.out.println("Returns an int:");
		System.out.println(Integer.toString(intReturner(false)));

		System.out.println("Returns a null:");
		System.out.println(Integer.toString(intReturner(true)));
	}
	
	private static int intReturner(Boolean returnNull){
		if (returnNull){
			return null;
		}else{
			return 2011;
		}
	}
}