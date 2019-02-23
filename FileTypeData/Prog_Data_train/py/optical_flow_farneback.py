import cv2
import numpy
import sys
from matplotlib import pyplot as pyp

file1 = '../data/bdcropped.png'
file2 = '../data/adcropped.png'

def preproc(filename):
	im = cv2.imread(file1)
	im_gray = cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
	im_blur = cv2.GaussianBlur(im_gray, (5,5), 1.4)
	im_sobel = cv2.Sobel(im_blur, -1, 1, 1)
	return im_sobel

bd = preproc(file1)
ad = preproc(file2)
flow = cv2.calcOpticalFlowFarneback(bd, ad, None, 0.5, 10, 5, 5, 5, 1.1, cv2.OPTFLOW_FARNEBACK_GAUSSIAN)
print np.sum(np.sum(np.sum(flow)))
pyp.quiver(flow)
pyp.show()

