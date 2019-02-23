
#include "TotalCallEventDuration_XmlTag.hpp"

using namespace tinyxml2;

void TotalCallEventDuration_XmlTag::create(XMLDocument& doc, XMLElement* const basicCallInformation){
    XMLElement* totalCallEventDuration = doc.NewElement("totalCallEventDuration");
    XMLText* totalCallEventDurationText = doc.NewText("");
    totalCallEventDuration->LinkEndChild(totalCallEventDurationText);
    basicCallInformation->LinkEndChild(totalCallEventDuration);
}

