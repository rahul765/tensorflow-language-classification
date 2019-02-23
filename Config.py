#!/usr/bin/env python3
# -*- coding: utf-8 -*-


# Path for testing and training data.
_PATH_TRAIN= 'FileTypeData/Prog_Data_train'     # Directory for Train language folders
_PATH_TEST = 'FileTypeData/Prog_Data_testing'   # Directory for Test language folders
_PATH_KEYWORDS = 'keywords.json'                # Directory for Keyword file with name and hash (48 digit) in json format
_PATH_LANGUAGE_JSON = 'languages.json'          # Directory for language json which defines languages we are training on

'''Processes.py'''
_NB_LINES = 100                                 # Default number of lines from which features can be extracted at once
_NB_FILES_MIN = 10                              # Minimum number of files required to train classifier



'''Neuran Network Configuration - LangPred.py'''
_NEURAL_NETWORK_HIDDEN_LAYERS = [256, 64, 16]       #Hidden layes in the network
_OPTIMIZER_STEP = 0.005                             #Lowering down the learning rate from 0.05 ro 0.005 to improve 
_FITTING_FACTOR = 20                                # To calculate the number of steps to be feeded to the neural network
_CHUNK_PROPORTION = 0.2                             # factor deciding the chunk size
_CHUNK_SIZE = 1000                                  # Maximum chunk that cam be passed to classifier at once


''' Feature Extraction'''
# FORMULA : merged_hash = (merged_hash * _FACTOR + short_hash) % CONTENT_SIZE

_SHIFT = 17                                     # Initial merged_hash
_FACTOR = 23                                    # Default factor to make new merged hash
CONTENT_SIZE = 2**10                            # Maximum value of merged hash i.e 1024


'''languages extention'''
extension = {
             "cpp": "C++",
             "py": "Python",
             "java": "Java",
             "cs" : "C Sharp",
             "php" : "Hypertext Preprocessor"
            }