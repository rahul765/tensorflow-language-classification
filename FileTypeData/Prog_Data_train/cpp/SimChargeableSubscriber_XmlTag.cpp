
#include "SimChargeableSubscriber_XmlTag.hpp"

using namespace tinyxml2;

void SimChargeableSubscriber_XmlTag::create(XMLDocument& doc, XMLElement* const chargeableSubscriber){
    XMLElement* simChargeableSubscriber = doc.NewElement("simChargeableSubscriber");
    imsi.create(doc, simChargeableSubscriber);
    chargeableSubscriber->LinkEndChild(simChargeableSubscriber);
}

