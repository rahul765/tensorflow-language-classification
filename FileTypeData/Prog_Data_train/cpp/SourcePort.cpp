#include "SourcePort.hpp"

using namespace std;

SourcePort::SourcePort(const std::string sourcePort) : sourcePort(sourcePort)
{
}

const char* const SourcePort::getPrintFormat() const
{
    return sourcePort.c_str();
}

bool SourcePort::operator==(const SourcePort& sPort) const
{
    return sPort.sourcePort == sourcePort;
}
