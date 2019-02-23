#include "DestinationPort.hpp"

using namespace std;

DestinationPort::DestinationPort(const std::string destinationPort) : destinationPort(destinationPort)
{
}

const char* const DestinationPort::getPrintFormat() const
{
    return destinationPort.c_str();
}

bool DestinationPort::operator==(const DestinationPort& dPort) const
{
    return dPort.destinationPort == destinationPort;
}




