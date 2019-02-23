package srm;

class techolympic14r1{
	public static void main(String[] args) {
		String [] words = {"Tech", "Hi", "Olympics"};
		int [] lw = new int [words.length];
		
		for(int t = 0 ; t < words.length ; t++ ){
			lw[t] = words[t].length();
		}
		
		int max = 0;
		String maxls = "";
		for(int t = 0 ; t < words.length ; t++ ){
			for(int l = t+1 ; l < words.length ; l++ ){
				
				if((lw[t] == 2*lw[l] || lw[l] == 2*lw[t]) && max <  lw[t] + lw[l]){
					max = lw[t] + lw[l];
					maxls = words[t] + words[l];
					if(lw[t] == 2*lw[l]){
						maxls = words[l] + words[t];
					}
				}
			}	
		}
	
		System.out.print(maxls);
	}
}

