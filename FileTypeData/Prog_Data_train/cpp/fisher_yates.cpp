#include <iostream>
#include <vector>
#include <algorithm>
using namespace std;

template<typename T>
void randomShuffle(vector<T>& element){

	auto counter = element.size();
	for(auto iter = element.rbegin(); iter != element.rend(); iter++, --counter) {
     int randomIndex = rand() % counter;
     swap(element.at(randomIndex), *iter);
	}
}

int main() {

  srand(time(nullptr));
  vector<int> elements;
  for (int i=0 ; i<10; i++) {
  	elements.push_back(i);
  }
  cout << "Initial set of elements is " << endl;
  for (auto item : elements) {
	  cout << item << " ";
  }
  cout<<endl;
  randomShuffle(elements);
  cout << "Randomized set of Int elements is " << endl;
  for (auto item : elements) {
	  cout << item << " ";
  }
  cout << endl;
  vector<string> alpha;
  alpha.push_back("a");
  alpha.push_back("b");
  alpha.push_back("c");
  alpha.push_back("d");
  randomShuffle(alpha);
  cout << "Randomized set of String elements is " << endl;
  for (auto item : alpha) {
    cout << item << " ";
  }
}