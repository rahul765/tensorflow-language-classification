#!/bin/env python
#-*- coding:utf-8 -*-
# import Image
from PIL import Image
# import ImageEnhance
# import ImageFilter
# import sys

ipath = '/Users/liujianlong/Downloads/v5.png'
im = Image.open(ipath)
#
imgry = im.convert('L')
imgry.save('gv5.png')

# imgry.save(ipath+'aa')
# out = imgry.point(table,'1')

# 二值化
threshold = 140

table = []
for i in range(256):
    if i < threshold:
        table.append(0)
    else:
        table.append(1)


def getverify1(name):
    #打开图片
    im = Image.open(name)
    #转化到灰度图
    imgry = im.convert('L')
    #保存图像
    imgry.save('g'+name)
    #二值化，采用阈值分割法，threshold为分割点
    out = imgry.point(table,'1')
    out.save('b'+name)
    # #识别
    # text = image_to_string(out)
    # #识别对吗
    # text = text.strip()
    # text = text.upper();
    # for r in rep:
    # text = text.replace(r,rep[r])
    # #out.save(text+'.jpg')
    # print text
    # return text

# print getverify1('./1.png')



