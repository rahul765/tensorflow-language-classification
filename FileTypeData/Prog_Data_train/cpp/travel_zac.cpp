#include <iostream>
#include <string>
#include <set>

using namespace std;

int main() {
  int n, t;
  cin >> t;
  
  while (t--) {
    cin >> n;
    set<string> s;
    while (n--) {
      string c;
      cin >> c;
      s.insert(c);
    }
    cout << s.size() << endl;
  }
  
  return 0;
}
