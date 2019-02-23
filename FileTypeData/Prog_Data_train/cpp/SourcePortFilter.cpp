
#include "SourcePortFilter.hpp"

using namespace std;

SourcePort SourcePortFilter::apply(const string line)
{
    return SourcePort(getSourcePort(sourceFilter.getSourcePartOfTheLine(line)));
}

string SourcePortFilter::getSourcePort(const string line) const
{
    return line.substr(line.rfind(".")+1);
}
