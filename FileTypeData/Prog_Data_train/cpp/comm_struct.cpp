#include "comm_struct.h"
#include <re2/re2.h>

bool Os::ParseUserAgent(std::string &ua)
{
    assert(m_isInit == true);
    for(std::vector<Os_struct>::iterator it = m_vecOsDb->begin(); it != m_vecOsDb->end(); it++) {
        if(RE2::FullMatch(ua.data(), it->os_regex.data())) {
            SetValue(*it);
            return true;
        }
    }
    return false;
}

bool Browser::ParseUserAgent(std::string &ua)
{
    assert(m_isInit == true);
    for(std::vector<Browser_struct>::iterator it = m_vecBrowserDb->begin(); it != m_vecBrowserDb->end(); it++) {
        if(RE2::FullMatch(ua.data(), it->browser_regex.data())) {
            SetValue(*it);
            return true;
        }
    }
    return false;
}
