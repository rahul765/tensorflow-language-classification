#include "InputClass.h"



InputClass::InputClass()
{
}


InputClass::~InputClass()
{
}

void InputClass::Initialize()
{
	for (int i = 0; i < 256; i++)
	{
		keys[i] = false;
	}
}

void InputClass::KeyDown(unsigned int input)
{
	keys[input] = true;
}

void InputClass::KeyUp(unsigned int input)
{
	keys[input] = false;
}

bool InputClass::IsKeyDown(unsigned int key)
{
	return keys[key];
}
