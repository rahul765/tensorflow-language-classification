
#include "CallReference_XmlTag.hpp"

using namespace tinyxml2;

void CallReference_XmlTag::create(XMLDocument& doc, XMLElement* const networkLocation){
    XMLElement* callReference = doc.NewElement("callReference");
    XMLText* callReferenceText = doc.NewText("");
    callReference->LinkEndChild(callReferenceText);
    networkLocation->LinkEndChild(callReference);
}

