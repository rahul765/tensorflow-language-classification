// reverse digits

#include <iostream>
using namespace std;

void reverseDigits(int num) {
	bool flag = false;
	if (num<0) {
		flag = true;
    num = -num;
	}
	int res = 0;
	while(num>0) {
		res = res*10 + num%10;
		num = num/10;
	}
  if(flag) {
  	cout<<-res<<endl;
  }
  else {
  	cout<<res<<endl;
  }
}

void reverseDigitsRecursive(int num) {
	bool flag = false;
	if (num<0) {
		num = -num;
		flag = true;
	}

	if (num<10){
		cout<<num<<endl;
		return;
	}
	if(flag)
		cout<<(num%10)*(-1);
	else
		cout<<num%10;

	reverseDigitsRecursive(num/10);
}

int main() {
	reverseDigits(12);
	reverseDigits(-12);
	reverseDigitsRecursive(-1213);
	cout<<atoi("-123")<<endl;
}