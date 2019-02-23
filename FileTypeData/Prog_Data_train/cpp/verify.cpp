#include <iostream>
#include <cassert>
#include <cctype>
#include <string>

using namespace std;

const int MAXT = 50;
const int MAXN = 100;
const int MAXL = 20;

void endline() {
  assert(cin.peek() == '\n');
  cin.ignore();
}

int getInt() {
  assert(isdigit(cin.peek()) || cin.peek() == '-');
  int t;
  assert(cin >> t);
  return t;
}

string getString() {
  assert(!cin.eof() && !isspace(cin.peek()));
  string s;
  assert(cin >> s);
  return s;
}

int main() {
  int t = getInt();
  endline();
  
  assert(1 <= t && t <= MAXT);

  while (t--) {
    int n = getInt();
    endline();
    
    assert(1 <= n && n <= MAXN);

    while (n--) {
      string s = getString();
      endline();
	    
      assert(1 <= s.length() && s.length() <= MAXL);
      for (auto c : s) assert(islower(c));
    }
  }
  assert(cin.peek() == EOF);

  return 0;
}
