#include <iostream>
using namespace std;
int main(){ 
	int N, L,dna[1000],nx,cs=1; cin >> N; short t;
	while (N--){
		cin >> L; nx = 0;
		for (t = 0; t<L; t++)cin >> dna[t];
		for (int p = 0; p < L - 1; p++)
			for (int k = p + 1; k <= L - 1; k++)
				if (dna[p] > dna[k]) nx++;
		printf("Scenario #%d:\n%d\n\n", cs++, nx);
	}
	return 0; }
