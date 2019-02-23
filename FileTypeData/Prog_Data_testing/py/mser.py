import cv2
import math
import numpy as np

d_red = cv2.cv.RGB(150, 55, 65)
l_red = cv2.cv.RGB(250, 200, 200)

file1 = '../../data/bdcropped.png'

orig = cv2.imread(file1)
img = orig.copy()
img2 = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

detector = cv2.MSER()
fs = detector.detect(img2, None)
fs.sort(key = lambda x: -x.size)
print(len(fs))

def supress(x):
        for f in fs:
                distx = f[0][0] - x[0][0]
                disty = f[0][1] - x[0][1]
                dist = math.sqrt(distx*distx + disty*disty)
                if dist < ((f.size + x.size)/2):
                        return True

sfs = [x for x in fs if not supress(x)]
print len(sfs)

for f in sfs:
        cv2.circle(img, (int(f[0][0]), int(f[0][1])), int(f.size/2), d_red, 2, cv2.CV_AA)
        cv2.circle(img, (int(f[0][0]), int(f[0][1])), int(f.size/2), l_red, 1, cv2.CV_AA)

h, w = orig.shape[:2]
vis = np.zeros((h, w*2+5), np.uint8)
vis = cv2.cvtColor(vis, cv2.COLOR_GRAY2BGR)
vis[:h, :w] = orig
vis[:h, w+5:w*2+5] = img

# cv2.imshow("image", vis)
cv2.imwrite('../../data/mser_results/before_defm_blobs.png', vis)
cv2.waitKey()
cv2.destroyAllWindows()
