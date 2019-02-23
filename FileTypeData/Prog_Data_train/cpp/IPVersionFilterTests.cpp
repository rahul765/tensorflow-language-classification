
#include "LineExamples.hpp"

#include "IPVersionFilter.hpp"
#include "IPVersion.hpp"

#include <gtest/gtest.h>
#include <string>

using namespace std;

TEST(IPVersionFilterTests, appliedFilterToIPv4Line)
{
    IPVersion ipVersionExpected(LineExamples::ipVersion4);
    IPVersionFilter filter;
    IPVersion ipVersionActual = filter.apply(LineExamples::line);

    EXPECT_TRUE(ipVersionExpected==ipVersionActual);
}
