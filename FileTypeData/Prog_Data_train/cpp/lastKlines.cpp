#include <iostream>
#include <fstream>
#include <vector>

using namespace std;

void printLastKLines(string filename, const int& k) {
  if(filename.size() == 0 || k <= 0) {
    return;
  }

  ifstream file(filename);
  if(!file.is_open()) {
    cout << "Error in opening file " <<endl;
    return;
  }

  vector<string> buf(k);
  int size = 0;
  while(getline(file, buf[size%k] )) {
    size++;
  }

  int start = size>k?(size%k):0;
  int count = min(count, size);
  for(int i=0; i<count; i++) {
    cout<<buf[(start+i)%k] << endl;
  }
}

int main() {
  printLastKLines("moby_dick.txt", 10);
}