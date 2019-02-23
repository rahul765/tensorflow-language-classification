package srm;

class srm212 {
	public static void main(String[] args) {

		srm212 obj = new srm212();
		String grid[] = { "############",
				  "###......###",
				  "##.######.##",
				  "#.########.#",
				  "#.##..####.#",
				  "#.##..####.#",
				  "#.########.#",
				  "#.########.#",
				  "#.########.#",
				  "##.######.##",
				  "###......###",
				  "############" };

		System.out.print(obj.radius(grid));
	}

	int radius(String[] grid) {
		int N = grid.length;
		int lsl = 0;
		for (int i = 0; i < N - lsl; i++) {
			for (int j = 0; j < N - lsl; j++) {
				for (int l = lsl; l < N; l++) {
					int flag = 0;
					for (int k = i; k < i + l && i + l < N; k++) {
						for (int m = j; m < j + l && j + l < N ; m++) {
							if(grid[k].charAt(m) == '#'){
								flag = 1;
							}
							if(flag == 0){
								flag = 2;
							}
							
						}
					}
					
					if (flag == 2){
						lsl = l;
					}
				}
			}
		}

		return lsl/2;
	}
}