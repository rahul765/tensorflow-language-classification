#include "Date.hpp"

using namespace std;

Date::Date(const std::string date) : date(date)
{
}

const char* const Date::getPrintFormat() const
{
    return date.c_str();
}

bool Date::operator==(const Date& da) const
{
    return da.date == date;
}
