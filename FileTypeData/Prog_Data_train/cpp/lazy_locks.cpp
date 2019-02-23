/**
  * Unique lock and lazy init.
  * Bo Qian
  */
#include <iostream>
#include <fstream>
using namespace std;

class LogFile {
  private:
    mutex mu;
    once_flag _fl;
    ofstream _f;

  public:
    void sharedPrint(string id, int value) {
      //open file only once and keep it thread safe.
      //if conditions make it unsafe and performance hit
      call_once(_fl, [&]() { _f.open("log.txt"); });

       unique_lock<mutex> locker(mu, defer_lock);
    }
};

int main() {

}