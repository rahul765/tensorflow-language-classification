
#include "XMLMessageGenerator.hpp"

using namespace tinyxml2;

void XMLMessageGenerator::create_message(XMLDocument& doc){
    XMLElement* dataInterChange = doc.NewElement("DataInterChange");  
    XMLElement* transferBatch = doc.NewElement("transferBatch");  

    batchControlInfo.create(doc, transferBatch);
    accountInfo.create(doc, transferBatch);
    networkInfo.create(doc, transferBatch);
    callEventDetails.create(doc, transferBatch);
    auditControlInfo.create(doc, transferBatch);

	dataInterChange->LinkEndChild(transferBatch);
	doc.LinkEndChild(dataInterChange);
}

