"""Machine learning model for programming identification"""

import os
import gc
from math import ceil
import json

import numpy as np
import tensorflow as tf



from FeatureExtract import extract, CONTENT_SIZE
from Proccess import (search_files, extract_from_files)
#Importing all the constant values from Config.py
from Config import _PATH_LANGUAGE_JSON, _NEURAL_NETWORK_HIDDEN_LAYERS, _OPTIMIZER_STEP, _FITTING_FACTOR ,_CHUNK_PROPORTION ,_CHUNK_SIZE

# Settings list
# LOGGER = logging.getLogger(__name__)


class Predictor:

    def __init__(self, model_dir=None):

        # trained model dir
        self.model_dir = os.curdir


        self.languages  = json.load(open(_PATH_LANGUAGE_JSON,"r"))

        n_classes = len(self.languages)
        feature_columns = [
            tf.contrib.layers.real_valued_column('', dimension=CONTENT_SIZE)]
      
        
        self._classifier = tf.contrib.learn.DNNLinearCombinedClassifier(
            linear_feature_columns=feature_columns,
            dnn_feature_columns=feature_columns,
            dnn_hidden_units=_NEURAL_NETWORK_HIDDEN_LAYERS,
            n_classes=n_classes,
            linear_optimizer=tf.train.AdamOptimizer(_OPTIMIZER_STEP), 
            dnn_optimizer=tf.train.AdamOptimizer(_OPTIMIZER_STEP), 
            model_dir=self.model_dir,
            dnn_activation_fn=tf.nn.log_softmax,
            dnn_dropout=0)
        # Test Data Accuracy : 96.03
        # Training Data Accuracy :  99.4
        

    ''' 
    Using the above classifier it predict the probality of the file belonging to one of 5 languages.
    After that it returns the language whose probality is maximum.
    '''
    def language(self, text):
        # predict language
        values = extract(text)
        input_fn = _to_func([[values], []])
        proba = next(self._classifier.predict_proba(input_fn=input_fn))
        proba = proba.tolist()

        # Order the languages from the most probable to the least probable
        positions = np.argsort(proba)[::-1]
        names = np.sort(list(self.languages))
        names = names[positions]
        
        return names[0]

    '''
        train the training data on model and return the accuracy it is able to achieve using diffrent chunk of data
        keeping  memory overflow in mind.
        NOTE : Functioning of lines are individually explained below.
    '''
    
    
    def learn(self, input_dir):
        """Learning model"""
        
        # Fetching the extentions from json file
        languages = self.languages
        extensions = [ext for exts in languages.values() for ext in exts]
        print (extensions)
        
        # Fetching random file from input directory(which should have more than _NB_FILES_MIN)
        files = search_files(input_dir, extensions)
        nb_files = len(files)
        
        #Setting how much data to push into neural network at once
        # minimum of 0.2*number of files or 1000
        chunk_size = min(int(_CHUNK_PROPORTION * nb_files), _CHUNK_SIZE)
        
        #Fit the chunk into the memory and avoid memory overflow
        batches = _pop_many(files, chunk_size)
        
        #Initiate multiprocessing and get the evaluation data in batches
        evaluation_data = extract_from_files(next(batches), languages)
        
        # Initializing the accuracy to 0
        accuracy = 0
        total = ceil(nb_files / chunk_size) - 1  # Unused
        
        print("Start learning")
        for pos, training_files in enumerate(batches, 1):
            
            #fetch the training data in batches already allotted at the top
            training_data = extract_from_files(training_files, languages)
            
            # Calculating number of steps to be passed into classifier
            steps = int(_FITTING_FACTOR * len(training_data[0]) / 100)
            
            #fitting the classifier on training data and steps according to the _FITTING_FACTOR currently initialized to 20
            self._classifier.fit(input_fn=_to_func(training_data), steps=steps)

            # Put the evaluation data into class classifier to train the model and fetch accuracy.
            accuracy = self._classifier.evaluate(
                input_fn=_to_func(evaluation_data), steps=1)['accuracy']

        return accuracy
    
    
'''
It go over the items and see if the chunk is fitting the memory 
else it exclude items if they go beyond memory in order to avoid memory overflow
and then its colletected by garbage collector.
'''
def _pop_many(items, chunk_size):
    while items:
        yield items[0:chunk_size]

        # Avoid memory overflow
        del items[0:chunk_size]
        gc.collect()

'''
Return constant tensor named as const_features and const_labels.
'''
def _to_func(vector):
    return lambda: (
        tf.constant(vector[0], name='const_features'),
        tf.constant(vector[1], name='const_labels'))
