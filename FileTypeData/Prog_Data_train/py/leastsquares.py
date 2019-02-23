import cv2

bdcropped = cv2.imread('../data/bdcropped.png')
grayscale_image = cv2.cvtColor(bdcropped, cv2.COLOR_BGR2GRAY)
circles = cv2.HoughCircles(grayscale_image, cv2.cv.CV_HOUGH_GRADIENT, 1, 20)
