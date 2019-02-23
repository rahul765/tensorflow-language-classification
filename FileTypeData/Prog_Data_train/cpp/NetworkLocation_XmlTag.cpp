
#include "NetworkLocation_XmlTag.hpp"

using namespace tinyxml2;

void NetworkLocation_XmlTag::create(XMLDocument& doc, XMLElement* const locationInformation){
    XMLElement* networkLocation = doc.NewElement("networkLocation");

    recEntityCode.create(doc, networkLocation);
    callReference.create(doc, networkLocation);
    locationArea.create(doc, networkLocation);
    cellId.create(doc, networkLocation);

    locationInformation->LinkEndChild(networkLocation);
}

