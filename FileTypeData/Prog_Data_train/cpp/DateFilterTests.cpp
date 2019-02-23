
#include "LineExamples.hpp"

#include "DateFilter.hpp"
#include "Date.hpp"

#include <gtest/gtest.h>
#include <string>

using namespace std;

TEST(DatesFilterTests, appliedFilterToIPv4Line)
{
    Date dateExpected(LineExamples::dateLine);
    DateFilter filter;
    Date dateActual = filter.apply(LineExamples::line);

    EXPECT_TRUE(dateExpected==dateActual);
}
