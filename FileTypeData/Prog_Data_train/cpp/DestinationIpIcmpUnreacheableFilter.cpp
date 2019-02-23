
#include "DestinationIpIcmpUnreacheableFilter.hpp"

using namespace std;

string DestinationIpIcmpUnreacheableFilter::getDestinationIp(const string line) const
{
    return line.substr(0, line.rfind(":"));
}
