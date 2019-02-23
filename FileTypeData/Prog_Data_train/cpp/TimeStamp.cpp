#include "TimeStamp.hpp"

TimeStamp::TimeStamp(Date date, Time time) : date(date), time(time)
{
}

Date TimeStamp::getDate() const
{
    return date;
}

Time TimeStamp::getTime() const
{
    return time;
}
