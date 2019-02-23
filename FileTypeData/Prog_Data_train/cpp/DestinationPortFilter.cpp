
#include "DestinationPortFilter.hpp"
#include "SymbolNotFoundException.hpp"

#include <iostream>

using namespace std;

DestinationPort DestinationPortFilter::apply(const string line)
{
    try
    {
        string tmp = destinationFilter.getDestinationIpAndPort(destinationFilter.getDestinationPartOfTheLine(line));
        return DestinationPort(getDestinationPort(tmp));
    }
    catch(SymbolNotFoundException& e)
    {
        string mockLine = "";
        return DestinationPort(getDestinationPort(mockLine));
    }
}

string DestinationPortFilter::getDestinationPort(const string line) const
{
    return line.substr(line.rfind(".")+1);
}
