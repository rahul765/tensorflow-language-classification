#include <iostream>
#include <fstream>
#include <stack>
using namespace std;

int main() {

  ifstream input_file;
  input_file.open("sample_file.txt");
  if (!input_file.good()) {
  	cout << "Unable to read file"<<endl;
  	return false;
  }
  stack<string> lines;
  string temp;
  while(input_file.good()) {
  	getline(input_file, temp, '.');
  	reverse(temp.begin(), temp.end());
  	lines.push(temp);
  }
  while (!lines.empty()) {
  	cout << lines.top() <<endl;
  	lines.pop();
  }
}