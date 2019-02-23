
#include "LocationInformation_XmlTag.hpp"

using namespace tinyxml2;

void LocationInformation_XmlTag::create(XMLDocument& doc, XMLElement* const mobileOriginatedCall){
    XMLElement* locationInformation = doc.NewElement("locationInformation");
    networkLocation.create(doc, locationInformation);
    mobileOriginatedCall->LinkEndChild(locationInformation);
}

