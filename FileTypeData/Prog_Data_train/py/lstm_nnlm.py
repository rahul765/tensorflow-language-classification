#!/usr/bin/python
# -*- coding: UTF-8 -*-

"""
@desc:   last实现的nnlm模型
@time:   2017/07/09 16:25
@author: liuluxin(0_0mirror@sina.com)
@param:
"""
import numpy as np
import tensorflow as tf
from tensorflow.models.rnn.ptb import reader


DATA_PATH = "../../datasets/PTB_data"
HIDDEN_SIZE = 200
NUM_LAYERS = 2
VOCAB_SIZE = 10000

LEARNING_RATE = 1.0
TRAIN_BATCH_SIZE = 20
TRAIN_NUM_STEP = 35

EVAL_BATCH_SIZE = 1
EVAL_NUM_STEP = 1
NUM_EPOCH = 2
KEEP_PROB = 0.5
MAX_GRAD_NORM = 5
