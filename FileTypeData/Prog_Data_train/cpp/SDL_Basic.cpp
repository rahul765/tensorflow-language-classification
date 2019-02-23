//============================================================================
// Name        : SDL_Basic.cpp
// Author      : ThomasWilliams
// Version     :
// Copyright   : Copyright 2016 Thomas Williams
// Description : Basic SDL program that displays a white screen. Can now be
//				 used in later projects, to create the Coding Math library
//				 in C++
//============================================================================

#include <iostream>
#include <SDL.h>
#include "Screen.h"

using namespace std;

int main(int argc, char* argv[]) {

	codingmath::Screen screen;

	if (!screen.init()) {
		cout << "Error initialising SDL." << endl;
	}

	while (true) {
		// Game loop here

		// Draw the screen
		screen.update();

		// Check for messages/events
		if (!screen.processEvents()) {
			break;
		}
	}

	screen.close();

	return 0;
}
