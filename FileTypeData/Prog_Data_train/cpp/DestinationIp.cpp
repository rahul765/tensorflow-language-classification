#include "DestinationIp.hpp"

using namespace std;

DestinationIp::DestinationIp(const std::string destinationIp) : destinationIp(destinationIp)
{
}

const char* const DestinationIp::getPrintFormat() const
{
    return destinationIp.c_str();
}

bool DestinationIp::operator==(const DestinationIp& dIp) const
{
    return dIp.destinationIp == destinationIp;
}
