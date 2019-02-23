#include "IPVersionFilter.hpp"

using namespace std;

IPVersion IPVersionFilter::apply(const string line)
{
    return IPVersion(line.substr(66, 4));
}
