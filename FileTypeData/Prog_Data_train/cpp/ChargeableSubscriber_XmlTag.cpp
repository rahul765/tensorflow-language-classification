
#include "ChargeableSubscriber_XmlTag.hpp"

using namespace tinyxml2;

void ChargeableSubscriber_XmlTag::create(XMLDocument& doc, XMLElement* const basicCallInformation){
    XMLElement* chargeableSubscriber = doc.NewElement("chargeableSubscriber");
    simChargeableSubscriber.create(doc, chargeableSubscriber);
    basicCallInformation->LinkEndChild(chargeableSubscriber);
}

