#include "VideoInputGeneric.h"

#include <opencv2/highgui/highgui.hpp>
#include <iostream>

using namespace cv;
using namespace std;

void VideoInputGeneric::init(int camID)
{
	capture = new VideoCapture(camID);
	if (!capture->isOpened())
	{
		cout << "Cannot open input camera, ID= " << camID << endl;
	}
}

void VideoInputGeneric::init(const char *filename)
{
	capture = new VideoCapture(filename);
	if (!capture->isOpened())
	{
		cout << "Cannot open input file: " << filename << endl;
	}
}

bool VideoInputGeneric::captureFrame(Mat &frame)
{
	*capture >> frame;
	if (frame.empty())
	{
		return false;
	}
	return true;
}

void VideoInputGeneric::release()
{
	if (capture != NULL)
	{
		delete capture;
		capture = NULL;
	}
}

void VideoInputGeneric::JumpToFrame(int frameIndex)
{
	capture->set(CV_CAP_PROP_POS_FRAMES, frameIndex);
}
