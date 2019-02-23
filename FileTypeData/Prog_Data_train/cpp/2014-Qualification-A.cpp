#include <iostream>
using namespace std;
int main(){
	short r[16], ra, in[4][4], temp, tmp, t;
	short cases, nc = 1; cin >> cases;
	while (cases--){
		memset(r, 0, sizeof(r)); t = 2;
		while (t--&&cin >> ra)
		{
			for (tmp = 0; tmp < 4; tmp++)for (temp = 0; temp < 4; temp++)cin >> in[tmp][temp];
			for (tmp = 0; tmp < 4; tmp++)r[in[ra - 1][tmp] - 1]++;
		}
		t = 0;
		for (tmp = 0; tmp < 16; tmp++)if (r[tmp] == 2)t++;
		printf("Case #%d: ", nc++); tmp = 0;
		if (t)
			if (t == 1){ while (r[tmp] != 2)tmp++; printf("%d\n", tmp + 1); }
			else printf("Bad magician!\n"); else printf("Volunteer cheated!\n");
	}
	return 0;
}
