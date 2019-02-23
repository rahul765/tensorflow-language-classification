#include "useragent.h"

int main(int argc, char **argv)
{
    UA_Init(argc, argv);
    class UserAgent ua;
    ua.Init();
    std::string temp = "Mozilla/5.0 (X11; Linux x86_64; rv:18.0) Gecko/20100101 Firefox/18.0";
    ua.Parse(temp);
    Browser_struct bValue;
    Os_struct oValue;
    ua.GetValue(oValue, bValue);
    std::cout <<  bValue << oValue;
    return 0;
}
