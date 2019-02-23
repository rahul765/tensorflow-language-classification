
#include "CellId_XmlTag.hpp"

using namespace tinyxml2;

void CellId_XmlTag::create(XMLDocument& doc, XMLElement* const networkLocation){
    XMLElement* cellId = doc.NewElement("cellId");
    XMLText* cellIdText = doc.NewText(cellIdGenerator.go());
    cellId->LinkEndChild(cellIdText);
    networkLocation->LinkEndChild(cellId);
}

