
#include "AuditControlInfo_XmlTag.hpp"

using namespace tinyxml2;

void AuditControlInfo_XmlTag::create(XMLDocument& doc, XMLElement* const transferBatch){
    XMLElement* auditControlInfo = doc.NewElement("auditControlInfo");  
    XMLText* auditControlInfoText = doc.NewText("");
    auditControlInfo->LinkEndChild(auditControlInfoText);
	transferBatch->LinkEndChild(auditControlInfo);
}


