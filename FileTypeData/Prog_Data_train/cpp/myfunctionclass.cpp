#include "myfunctionclass.h"

MyFunctionClass::MyFunctionClass()
{
    //ctor
}

MyFunctionClass::~MyFunctionClass()
{
    //dtor
}
std::string MyFunctionClass::ConvertToString(wxTextCtrl *mytext)
{
    std::string str;
    str=mytext->GetValue().char_str();
    return str;
}
