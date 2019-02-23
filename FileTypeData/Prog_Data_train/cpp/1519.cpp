#include <iostream>
using namespace std;
int main(){ 
	int i; char input[10000]; short t;
	//题目中不指定输入长度是个坑
	while (cin>>input){
		i = 0;
		if (strlen(input)==1&& input[0]=='0') return 0;
		for (t = 0; t < strlen(input); t++) {i += input[t] - 0x30;
		if (i > 9)i = i / 10 + i % 10;
		}
		printf("%d\n", i);
	}
 }
