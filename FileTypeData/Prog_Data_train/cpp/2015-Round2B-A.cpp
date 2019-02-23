//Result AC on small data, TLE on big data
#define LLB 0x7fffFFFFffffFFFF
typedef long long LL;
#define IB 0x7fffFFFF
#define PB push_back
#define PF push_front
#include<iostream>
#include<algorithm>
#include<cstring>
#include<string>
using namespace std;
struct tnode{
	int father; int color;
}tree[1000001];
int main(){
	int __, n,t,tt,q,p,op; cin >> __;
	for (int _=1; _ <= __; _++){
		cin >> n;
		for (int i = 1; i <= n; i++){
			tree[i].color = 0;
			tree[i].father = 1;
		}
		for (int i = 1; i <n; i++){
			scanf("%d%d", &t, &tt);
			if (t>tt)swap(t, tt);
			tree[tt].father = t;
		}
		scanf("%d", &q); printf("Case #%d:\n", _);
		while (q--){
			scanf("%d", &p);
			if (p == 2){
				scanf("%d%d", &t, &tt);
				tree[t].color = tt;
			}
			else{
				op = 1;
				for (int i = 2; i <=n; i++)
					if (tree[tree[i].father].color != tree[i].color)op++;
				printf("%d\n", op);
			}
		}
		
	}
	return 0;
}
