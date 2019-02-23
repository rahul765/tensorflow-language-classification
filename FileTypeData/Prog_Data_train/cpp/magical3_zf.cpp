//could sieve all 16-bit primes to speed up a bit
//didn't bother

#include <iostream>

using namespace std;

typedef unsigned long long ULL;

ULL prime(ULL n) {
  for (ULL i = 2; i*i <= n; ++i)
    if (n%i == 0)
      return i;
  return n;
}

ULL solve(ULL n) {
  for (ULL m = 4; m <= 10; ++m)
    if (n%m == 3)
      return m;
  if (n <= 6) return -1;

  ULL p = prime(n-3);
  return (p > 3 ? p : prime((n-3)/p));
}

int main() {
  int n;
  while ((cin >> n) && n) {
    int s = solve(n);
    if (s == -1) cout << "No such base" << endl;
    else cout << s << endl;
  }
  return 0;
}
