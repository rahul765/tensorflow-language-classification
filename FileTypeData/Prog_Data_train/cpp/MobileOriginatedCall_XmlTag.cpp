
#include "MobileOriginatedCall_XmlTag.hpp"

using namespace tinyxml2;

void MobileOriginatedCall_XmlTag::create(XMLDocument& doc, XMLElement* const callEventDetails){
    XMLElement* mobileOriginatedCall = doc.NewElement("mobileOriginatedCall");

    basicCallInformation.create(doc, mobileOriginatedCall);
    locationInformation.create(doc, mobileOriginatedCall);
    basicServiceUsedList.create(doc, mobileOriginatedCall);
    operatorSpecInformation.create(doc, mobileOriginatedCall);

    callEventDetails->LinkEndChild(mobileOriginatedCall);
}



