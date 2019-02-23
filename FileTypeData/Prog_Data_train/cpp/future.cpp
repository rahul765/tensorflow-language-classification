/**
 * Sample future and promise
 */
#include <iostream>
#include <thread>
#include <future>
using namespace std;

int factorial(int number) {
  int temp = 1;
  for(int i=number; i>1; i--) {
    temp *= i;
  }
  cout <<"Factorial of" << number << "is " <<temp;
  return temp;
}

int main() {
  int x;
  //implementation determines if to start a new thread or wait till get is called.
  future<int> f = async(launch::deferred | launch::async, factorial, 4);
  x = f.get();

  return 0;
}