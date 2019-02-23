#include <iostream>
#include <assert.h>

#include "ConfigManagerBase.h"

using namespace MiscTimeAndConfig;

bool ConfigManagerBase::init(char *filename)
{
    // load from a data file
    SI_Error rc = ini.LoadFile(filename);
	assert(rc >= 0);

	return this->readConfiguration(&ini);
}

bool ConfigManagerBase::readConfiguration(CSimpleIniA *ini)
{
	assert(false);	// This method should not be called in the base class.
	return false;
}