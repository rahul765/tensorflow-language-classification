
#include "BasicServiceUsedList_XmlTag.hpp"

using namespace tinyxml2;

void BasicServiceUsedList_XmlTag::create(XMLDocument& doc, XMLElement* const mobileOriginatedCall){
    XMLElement* basicServiceUsedList = doc.NewElement("basicServiceUsedList");
    XMLText* basicServiceUsedListText = doc.NewText("");
    basicServiceUsedList->LinkEndChild(basicServiceUsedListText);
    mobileOriginatedCall->LinkEndChild(basicServiceUsedList);

}

