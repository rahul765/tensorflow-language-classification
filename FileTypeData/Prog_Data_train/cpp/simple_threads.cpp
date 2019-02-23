#include <iostream>
#include <thread>
#include <mutex>
#include <vector>
using namespace std;

const long NUM_THREADS = 20;
const long INCREMENTS = 100;
mutex mu;

void printNumbers(int id) {

  for(int i=0; i<INCREMENTS; i++) {
  	lock_guard<mutex> guard(mu);
	  cout <<"The count is "<< i << " from thread "<<(id+1)<<endl;
  }
}

int main() {

  vector<thread> threadArray;
  for(int i=0; i<NUM_THREADS; i++) {
  	lock_guard<mutex> guard(mu);
	  threadArray.push_back(thread(printNumbers, i));  //starts running the threads
  }

  for(auto& t : threadArray) {
  	t.join();
  }
}