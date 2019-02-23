
#include "RecEntityCode_XmlTag.hpp"

using namespace tinyxml2;

void RecEntityCode_XmlTag::create(XMLDocument& doc, XMLElement* const networkLocation){
    XMLElement* recEntityCode = doc.NewElement("recEntityCode");
    XMLText* recEntityCodeText = doc.NewText("");
    recEntityCode->LinkEndChild(recEntityCodeText);
    networkLocation->LinkEndChild(recEntityCode);
}

