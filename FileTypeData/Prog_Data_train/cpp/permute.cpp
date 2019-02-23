#include <iostream>
#include <string>
using namespace std;

void swap(char* x, char* y){
	char temp;
	temp = *x;
	*x = *y;
	*y = temp;
}

void permute(char* a,int l,int r ){
	if(l==r) cout << a << endl;
	else {
		for(int i=l;i<=r;i++){
			swap(a+i,a+l);
			permute(a,l+1,r);
			swap(a+i,a+l);		
		}	
	}
}

int main(){
	string temp;
	cin >> temp;
	permute(&temp[0],0,temp.length()-1);
	return 0;
}
