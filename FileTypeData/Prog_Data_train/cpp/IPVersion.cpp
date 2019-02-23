#include "IPVersion.hpp"

using namespace std;

IPVersion::IPVersion(const std::string version) : version(version)
{
}

const char* const IPVersion::getPrintFormat() const
{
    return version.c_str();
}

bool IPVersion::operator==(const IPVersion& ipVers) const
{
    return ipVers.version == version;
}
