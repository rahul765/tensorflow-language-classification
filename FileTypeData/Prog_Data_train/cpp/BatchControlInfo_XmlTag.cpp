
#include "BatchControlInfo_XmlTag.hpp"

using namespace tinyxml2;

void BatchControlInfo_XmlTag::create(XMLDocument& doc, XMLElement* const transferBatch){
    XMLElement* batchControlInfo = doc.NewElement("batchControlInfo");  
    XMLElement* sender = doc.NewElement("sender");  
    XMLText* senderText = doc.NewText("");
    sender->LinkEndChild(senderText);
	batchControlInfo->LinkEndChild(sender);
	transferBatch->LinkEndChild(batchControlInfo);
}

