#include <iostream>
#include <string>
#include <vector>

using namespace std;

typedef vector<bool> VB;

int main() {
  int t;
  cin >> t;
  
  while (t--) {
    int r, c;
    cin >> r >> c;

    vector<string> g(r);

    VB row(r, false), col(c, false);
    
    for (int i = 0; i < r; ++i) {
      cin >> g[i];
      for (int j = 0; j < c; ++j)
	if (g[i][j] == '1')
	  row[i] = col[j] = true;
    }

    int nr = 0, nc = 0;
    for (auto x : row) if (x) ++nr;
    for (auto x : col) if (x) ++nc;

    char fill = (nr == 1 || nc == 1) ? 'P' : 'I';
    
    bool ok = true;
    for (int i = 0; i < r; ++i)
      for (int j = 0; j < c; ++j) {
	ok &= (!row[i] || !col[j] || g[i][j] == '1');
	g[i][j] = (g[i][j] == '0' ? 'N' : fill);
      }

    if (!ok)
      cout << "impossible" << endl;
    else
      for (auto &x : g)
	cout << x << endl;

    cout << "----------" << endl;
  }
  return 0;
}
