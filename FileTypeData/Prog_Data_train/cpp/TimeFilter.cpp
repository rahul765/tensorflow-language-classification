#include "TimeFilter.hpp"

Time TimeFilter::apply(const std::string line)
{
    return Time(line.substr(11, 15));
}
