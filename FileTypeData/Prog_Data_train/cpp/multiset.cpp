#include "multiset.h"

using namespace std;

namespace cava
{
void Multiset::run()
{
    multiset<int> a;

    a.insert(0);
    a.insert(3);
    a.insert(10);

    multiset<int>::iterator mit;

    for (mit = a.begin(); mit != a.end(); ++mit) {
        cout << *mit << ", ";
    }
    cout << endl;

    multiset<string, less<string>> b;

    b.insert("New York");
    b.insert("Las Vegas");
    b.insert("Copenhaga");
    b.insert("Moscow");

    multiset<string>::iterator smit;

    for (smit = b.begin(); smit != b.end(); ++smit) {
        cout << *smit <<  ", ";
    }
    cout << endl;
}
}
