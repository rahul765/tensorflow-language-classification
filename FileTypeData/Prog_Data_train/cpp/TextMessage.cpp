#include <sstream>
#include <stdio.h>
#include "TextMessage.h"
#include "Logger.h"

TextMessage::TextMessage(char *json)
{
	typecode = Text;
	memset(content,0,TEXT_MESSAGE_BUFFER_SIZE);
	parse(json);
}

TextMessage::TextMessage()
{
	typecode = Text;
}

bool TextMessage::parse(char *json)
{
	JsonMessage::readFieldInto(json,"content",content);
	return true;
}

void TextMessage::writeJson(char *buffer)
{
	sprintf(buffer,"{ \"type\": \"text\", \"content\": \"%s\" }#",content);
}

void TextMessage::log()
{
	LogConfigTime::Logger::getInstance()->Log(LogConfigTime::Logger::LOGLEVEL_INFO,"Message","StringMessage(%s)\n",content);
}

void TextMessage::copyToContent(const char *srcText)
{
	strcpy(this->content,srcText);
}
