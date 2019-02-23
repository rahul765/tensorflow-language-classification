#include <iostream>
#include <string>
#include <vector>
#include <iomanip>
#include <cassert>

#define MAXR 20

using namespace std;

struct Ing {
  string s;
  double w, p;
};

int main() {
  cout.setf(ios::fixed);
  cout.precision(1);

  int t;
  Ing ing[MAXR];
  cin >> t;
  for (int cno = 1; cno <= t; ++cno) {
    int r;
    double p, d;

    int mi = -1;
    cin >> r >> p >> d;

    for (int i = 0; i < r; ++i) {
      cin >> ing[i].s >> ing[i].w >> ing[i].p;
      if (ing[i].p == 100.0) {
	assert(mi == -1);
	mi = i;
      }
    }

    assert(mi != -1);

    double sf = d/p;
    double main_sw = ing[mi].w*sf;

    cout << "Recipe # " << cno << endl;
    for (int i = 0; i < r; ++i)
      cout << ing[i].s << ' ' << main_sw*ing[i].p / 100.0 << endl;

    cout << string(40,'-') << endl;
  }
  return 0;
}
