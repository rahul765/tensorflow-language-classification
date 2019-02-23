// O(n)

#include <iostream>
#include <vector>
#include <queue>

using namespace std;

int main() {
  int t;
  cin >> t;
  while (t--) {
    int n;
    cin >> n;
    vector<int> deg(2*n, 0);
    vector< vector<int> > pic(2*n);
    
    for (int i = 0; i < n; ++i) {
      int x, y;
      cin >> x >> y;
      --x; --y;
      pic[x].push_back(y);
      pic[y].push_back(x);
      ++deg[x];
      ++deg[y];
    }

    queue<int> q;
    for (int i = 0; i < 2*n; ++i)
      if (deg[i] == 1)
	q.push(i);

    while (!q.empty()) {
      int u = q.front();
      q.pop();
      if (deg[u] != 1) continue;

      for (auto v : pic[u])
	if (--deg[v] == 1)
	  q.push(v);
      --deg[u];
    }

    int nv = 0, ne = 0;
    for (int i = 0; i < 2*n; ++i)
      if (deg[i] > 0) {
	++nv;
	for (auto j : pic[i])
	  if (deg[j] > 0)
	    ++ne;
      }
    cout << (ne > 2*nv ? "impossible" : "possible") << endl;
  }

  return 0;
}
