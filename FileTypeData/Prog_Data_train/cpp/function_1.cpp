#include <iostream>
#include <cstdlib>

using namespace std;

void sum(int a, int b){
    int sum = a + b;
    cout<<"Sum is: "<<sum<<endl;
}

void power(int n, int m){
    int power = 1;
    for (int i = 1; i < m+1;i++){
        power = power * n;
    }
    cout<<"power is: "<<power<<endl;
}



int main(int argc, char* argv[]){
    int num1 = atoi(argv[1]);
    int num2 = atoi(argv[2]);
    sum(num1, num2);
    power(num1, num2);
    return 0;
}