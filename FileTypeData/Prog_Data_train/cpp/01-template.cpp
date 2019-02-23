#include <iostream>

using namespace std;

// 使用template生成Fibnacci数列
template <int N> int fib() {return fib<N-1>() + fib<N-2>();}
//特化
template <> int fib<1>() {return 1;}
template <> int fib<0>() {return 1;}

// 使用template结构体生成
template <int N> struct Fib {enum {value = Fib<N-1>::value + Fib<N-2>::value};};
//特化
template <> struct Fib<0> {enum {value = 1};};
template <> struct Fib<1> {enum {value = 1};};

int main()
{
  cout << "fib(3)\t= " << fib<30>() << endl;
  cout << "Fib(140)\t= " << Fib<240>::value << endl;
}
