
#include "SourceFilter.hpp"

using namespace std;

string SourceFilter::getRawSourceAndDestinationPartOfTheLine(const string line) const
{
    return line.substr(line.find(" "));
}

string SourceFilter::eraseTheFrontOfTheSourceAndDestinationPartOfTheLine(string line) const
{
    line.erase(line.begin());
    return line;
}

string SourceFilter::getSourceIpAndPort(const string line) const
{
    return line.substr(0, (line.find_first_of(" ")));
}

string SourceFilter::getSourcePartOfTheLine(const string line) const
{
    string tmp = lineFilter.extractLine(line);
    string tmp1 = lineFilter.getTheStablePartOfTheLine(tmp);
    string tmp2 = getRawSourceAndDestinationPartOfTheLine(tmp1);
    string tmp3 = eraseTheFrontOfTheSourceAndDestinationPartOfTheLine(tmp2);
    return getSourceIpAndPort(tmp3);
}
