// edit distance is the minimum number of edits required to
// convert one string to other. Three type of operations can be
// define. insert, delete and replace character
// Dumb way to do it is recursion, else dynamic programming.

#include <iostream>
#include <vector>
using namespace std;

int smallest (int x, int y, int z) {
	return min(min(x, y), z);
}

int editDistanceDumb(string s1, string s2, int i, int j) {

	if (i==0 && j==0) {
		return 0;
	}
	if (i==0) {
		return j;
	}

	if (j==0) {
		return i;
	}

	return smallest(editDistanceDumb(s1, s2, i-1, j), editDistanceDumb(s1, s2, i, j-1),
									(editDistanceDumb(s1, s2, i-1, j-1) + (s1[i-1] != s2[j-1])));
}

int main() {

	string s1 = "harsh";
	string s2 = "harsh";
	cout<< "The minimum edit distance is "<<editDistanceDumb(s1, s2, s1.length(), s2.length())<<endl;
}