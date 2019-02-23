/**
 * Send message from parent to child using futures and promise
 */
#include <iostream>
#include <future>
using namespace std;

int factorial(future<int>& f) {
  int result = 1;
  int number = f.get();
  for(int i=number; i>1; i--) {
    result *= i;
  }
  return result;
}

int main() {
  promise<int> p;
  future<int> f = p.get_future();
  future<int> ft = async(launch::async, factorial, ref(f));

  //sleep for some time
  this_thread::sleep_for(chrono::milliseconds(50));
  p.set_value(4);
  int x = ft.get();
}