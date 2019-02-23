#include<iostream>
using namespace std;
int p,paper;
int main(){
	while (cin>>p&&p){
		printf("Printing order for %d pages:\n",p);
		if (p<5){
			switch (p){
			case 1:puts("Sheet 1, front: Blank, 1");break;
			case 2:puts("Sheet 1, front: Blank, 1");puts("Sheet 1, back : 2, Blank");break;
			case 3:puts("Sheet 1, front: Blank, 1");puts("Sheet 1, back : 2, 3");break;
			case 4:puts("Sheet 1, front: 4, 1");puts("Sheet 1, back : 2, 3");
			}
		}else{
			paper=p/4+(p%4?1:0);
			for (int _=1;_<=paper;++_){
				if (4*paper-2*_+2>p)printf("Sheet %d, front: Blank, %d\n",_,2*_-1);
				else printf("Sheet %d, front: %d, %d\n",_,4*paper-2*_+2,2*_-1);
				if (4*paper-2*_+1>p)printf("Sheet %d, back : %d, Blank\n",_,2*_);
				else printf("Sheet %d, back : %d, %d\n",_,2*_,4*paper-2*_+1);
			}
		}
	}
}