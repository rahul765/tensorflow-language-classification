#include <iostream>
#include <cstdio>

using namespace std;

int main () {

	int  ages[]   = {0,          17,        2,          52,         25};
	char *names[] = {"None",     "Mike",    "Billy",    "Tom",      "Stan"};
	int  parent[] = {0 /*None*/, 3 /*Tom*/, 1 /*Mike*/, 0 /*None*/, 3 /*Tom*/};

	for(int i = 1; i <= 4; i++) {
	    printf("Name: %s, Age: %d, Parent: %s \n",
	           names[i], ages[i], names[parent[i]]);
	}

	cout << endl;
	
	return 0;
}