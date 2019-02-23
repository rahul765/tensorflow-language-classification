
#include "DestinationIpFilter.hpp"
#include "SymbolNotFoundException.hpp"

#include <iostream>

using namespace std;

DestinationIp DestinationIpFilter::apply(const string line) const
{
    try
    {
        string tmp = getDestinationIp(destinationFilter.getDestinationPartOfTheLine(line));
        return DestinationIp(tmp);
    }
    catch(SymbolNotFoundException& e)
    {
        string mockLine = "";
        return DestinationIp(getDestinationIp(mockLine));
    }
}

string DestinationIpFilter::getDestinationIp(const string line) const
{
    return line.substr(0, line.rfind("."));
}


