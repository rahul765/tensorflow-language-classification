
#include "OperatorSpecInformation_XmlTag.hpp"

using namespace tinyxml2;

void OperatorSpecInformation_XmlTag::create(XMLDocument& doc, XMLElement* const mobileOriginatedCall){
    XMLElement* operatorSpecInformation = doc.NewElement("operatorSpecInformation");
    XMLText* operatorSpecInformationText = doc.NewText("");
    operatorSpecInformation->LinkEndChild(operatorSpecInformationText);
    mobileOriginatedCall->LinkEndChild(operatorSpecInformation);
}

