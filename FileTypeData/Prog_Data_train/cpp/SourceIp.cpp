#include "SourceIp.hpp"

using namespace std;

SourceIp::SourceIp(const std::string sourceIp) : sourceIp(sourceIp)
{
}

const char* const SourceIp::getPrintFormat() const
{
    return sourceIp.c_str();
}

bool SourceIp::operator==(const SourceIp& sIp) const
{
    return sIp.sourceIp == sourceIp;
}
