
#include "LocationArea_XmlTag.hpp"

using namespace tinyxml2;

void LocationArea_XmlTag::create(XMLDocument& doc, XMLElement* const networkLocation){
    XMLElement* locationArea = doc.NewElement("locationArea");
    XMLText* locationAreaText = doc.NewText(locationAreaGenerator.go());
    locationArea->LinkEndChild(locationAreaText);
    networkLocation->LinkEndChild(locationArea);
}

