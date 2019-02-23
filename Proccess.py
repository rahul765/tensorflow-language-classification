"""A set of tools to process files"""

import logging
import multiprocessing
from pathlib import Path
import glob
import random
import signal
import sys

import numpy as np
from FeatureExtract import extract
from Config import _NB_LINES , _NB_FILES_MIN


LOGGER = logging.getLogger(__name__)

# generate pseudo random number every time 
random.seed()

'''
Check if the source is having the extentions for which you  want to train your neural network:
    If yes: then count and check if it is having sufficient number of files
    i.e. _NB_FILES_MIN. Default value = 10
    if no: then exit saying "Too few source files"
'''
def search_files(source, extensions):
    print (extensions)
    files = [
        path for path in Path(source).glob('*/*')
        if path.is_file() and path.suffix.lstrip('.') in extensions]
    nb_files = len(files)
    # LOGGER.debug("Total files found: %d", nb_files)
    print ("Total files found: ", nb_files)
    if nb_files < _NB_FILES_MIN:
        # LOGGER.error("Too few source files")
        print ("Too few source files (<",_NB_FILES_MIN,").")
        sys.exit()

    random.shuffle(files)
    return files


'''
Explaination already given.
'''
def extract_from_files(files, languages):
    """Extract arrays of features from the given files.

    :param list files: list of filenames
    :param dict languages: language name => associated file extension list
    :return: features
    :rtype: tuple
    """
    enumerator = enumerate(sorted(languages.items()))
    rank_map = {ext: rank for rank, (_, exts) in enumerator for ext in exts}

    with multiprocessing.Pool(initializer=_process_init) as pool:
        file_iterator = ((path, rank_map) for path in files)
        arrays = _to_arrays(pool.starmap(_extract_features, file_iterator))

    # LOGGER.debug("Extracted arrays count: %d", len(arrays[0]))
    print ("Extracted arrays count:", len(arrays[0]))
    return arrays


'''
In order to do multiprocessing, we need main process to be running. 
If it is aborted or somehow killed. Then subprocess must also be killed 
in order to same resources.
'''
def _process_init():
    # Stop the subprocess silently when the main process is killed
    signal.signal(signal.SIGINT, signal.SIG_IGN)


'''
Check if the file presnt on the path has the extention which is present in the rank map:
    if yes : the read and return the number of lines i.e. _NB_LINES  along with the rank
    else : currently we are ignoring that. In future we can extend it as we get wider range of extentions, maybe.
'''
def _extract_features(path, rank_map):
    ext = path.suffix.lstrip('.')
    rank = rank_map.get(ext)
    if rank is None:
        pass

    content = read_file(path)
    content = '\n'.join(content.splitlines()[:_NB_LINES])
    return [extract(content), rank]

'''
Return the np.array form of ranks and content_vectors presemt in features
'''
def _to_arrays(features):
    # Flatten and split the dataset
    ranks = []
    content_vectors = []
    for content_vector, rank in features:
        ranks.append(rank)
        content_vectors.append(content_vector)

    # Convert lists into numpy arrays
    return (np.array(content_vectors), np.array(ranks))

'''
On the defined file_path, read the text inside the file if it is in utf-8 format.
'''
def read_file(file_path):
    # read the files in 'utf-8' encoding; otherwise drop the file
    try:
        return file_path.read_text(encoding='utf-8')
    except UnicodeError:
        pass  # Ignore encoding error
