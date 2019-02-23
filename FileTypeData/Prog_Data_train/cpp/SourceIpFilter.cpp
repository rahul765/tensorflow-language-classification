
#include "SourceIpFilter.hpp"

#include <cstdio>

using namespace std;

SourceIp SourceIpFilter::apply(const string line)
{
    return SourceIp(getSourceIp(sourceFilter.getSourcePartOfTheLine(line)));
}

string SourceIpFilter::getSourceIp(const string line) const
{
    return line.substr(0, line.rfind("."));
}
