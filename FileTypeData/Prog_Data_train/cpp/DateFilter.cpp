#include "DateFilter.hpp"

Date DateFilter::apply(const std::string line)
{
    return Date(line.substr(0, 10));
}
