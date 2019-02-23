#include "SourceIpIcmpUnreacheableFilter.hpp"

using namespace std;

string SourceIpIcmpUnreacheableFilter::getSourceIp(const string line) const
{
    return line.substr(0, line.rfind(":"));
}
