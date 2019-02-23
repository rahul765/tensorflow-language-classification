
#include "LineExamples.hpp"

#include "TimeFilter.hpp"
#include "Time.hpp"

#include <gtest/gtest.h>
#include <string>

using namespace std;

TEST(TimeFilterTests, appliedFilterToIPv4Line)
{
    Time timeExpected(LineExamples::timeLine);
    TimeFilter filter;
    Time timeActual = filter.apply(LineExamples::line);

    EXPECT_TRUE(timeExpected==timeActual);
}
