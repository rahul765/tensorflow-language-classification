
#include "AccountInfo_XmlTag.hpp"

using namespace tinyxml2;

void AccountInfo_XmlTag::create(XMLDocument& doc, XMLElement* const transferBatch){
    XMLElement* accountingInfo = doc.NewElement("accountingInfo");  
    XMLElement* tapDecimalPlaces = doc.NewElement("tapDecimalPlaces");
    XMLText* tapDecimalPlacesText = doc.NewText("");
    tapDecimalPlaces->LinkEndChild(tapDecimalPlacesText);
	accountingInfo->LinkEndChild(tapDecimalPlaces);
	transferBatch->LinkEndChild(accountingInfo);
}

