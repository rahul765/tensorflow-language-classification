
#include "BasicCallInformation_XmlTag.hpp"

using namespace tinyxml2;

void BasicCallInformation_XmlTag::create(XMLDocument& doc, XMLElement* const mobileOriginatedCall){
    XMLElement* basicCallInformation = doc.NewElement("basicCallInformation");

    chargeableSubscriber.create(doc, basicCallInformation);
    callEventStartTimeStamp.create(doc, basicCallInformation);
    totalCallEventDuration.create(doc, basicCallInformation);

    mobileOriginatedCall->LinkEndChild(basicCallInformation);
}




