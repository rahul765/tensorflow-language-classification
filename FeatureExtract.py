"""Library for tokenizing and extracting feature vectors from a given text.
It can be done using sklearn.feature_extraction.text.HashingVectorizer also.
But going step by step using simple functions. """



import re
import math
import json
from Config import _SHIFT, _FACTOR, CONTENT_SIZE, _PATH_KEYWORDS
from nltk.corpus import stopwords

SPECIAL_KEYWORDS = {'num': '<number>', 'var': '<variable>'}

# import keywords as dictionary
_KEYWORDS = json.load(open(_PATH_KEYWORDS,"r"))

'''
Matches any character which is not word character.
'''
_SEPARATOR = re.compile(r'(\W)')
_REPLACE_WITH_SPACE_RE = re.compile('[/(){}\[\]\|@$,;]')
_UNWANTED_SYMBOLS_RE = re.compile('[^0-9a-z #+_]')
_STOPWORDS = set(stopwords.words('english'))

def extract(text):
    """Transform the text into a vector of float values.
    The vector is a representation of the text.

    :param str text: the text to represent
    :return: representation
    :rtype: list
    """
    return _normalize(_vectorize(split(text)))

'''Data cleaning and removing stop words - But, we will not use it. It will remove significant features
needed to recognize the language'''
def text_cleaning(text):
    text = text.lower()                             # lowercase text
    text = _REPLACE_WITH_SPACE_RE.sub(' ', text)     # replace defined symbols by space in text
    text = _UNWANTED_SYMBOLS_RE.sub('', text)        # delete unwanted symbols as defined from text
    text = ' '.join(word for word in text.split() if word not in _STOPWORDS)     # delete stopwors from text
    return text
    

def split(text):
    """Split a text into a list of tokens.

    :param str text: the text to split
    :return: tokens
    :rtype: list
    """
    return [word for word in _SEPARATOR.split(text) if word.strip(' \t')]


def _vectorize(tokens):
    # Turn list of tokens into a feature vector
    values = []
    for token in tokens:
        if token in _KEYWORDS_CACHE:
            values.append(_KEYWORDS_CACHE[token])
        elif token.isdigit():
            values.append(_KEYWORDS_CACHE[SPECIAL_KEYWORDS['num']])
        else:
            values.append(_KEYWORDS_CACHE[SPECIAL_KEYWORDS['var']])

    # Makes bi, tri, quad and penta-gram feature vectors
    bigrams = [_merge(values[pos:pos+2]) for pos in range(len(values) - 1)]
    trigrams = [_merge(values[pos:pos+3]) for pos in range(len(values) - 2)]
    quadgrams = [_merge(values[pos:pos+4]) for pos in range(len(values) - 3)]
    pentgrams = [_merge(values[pos:pos+5]) for pos in range(len(values) - 4)]
    values += bigrams + trigrams + quadgrams + pentgrams

    vector = [0] * CONTENT_SIZE
    for short_hash, weight in values:
        vector[short_hash] += weight

    return vector

# Merges together tokens for bi/tri/quad/penta grams
def _merge(hash_list):
    merged_hash = _SHIFT
    merged_weight = 1
    for short_hash, weight in hash_list:
        merged_hash = (merged_hash * _FACTOR + short_hash) % CONTENT_SIZE
        merged_weight *= weight
    return (merged_hash, merged_weight)


'''
Here we will go with L2 Norm as it provides stable, 
computationally efficient and non-sparse output.
'''
def _normalize(vector):
    length = math.sqrt(sum(value**2 for value in vector))
    if not length:
        return vector
    normalized = [value / length for value in vector]
    return normalized


'''
As mentioned. It's called to fill "_KEYWORDS_CACHE" from keywords.json 
along with new special keywords already in cache 
'''
def _prepare_cache():
    # Called to fill "_KEYWORDS_CACHE" dictionary
    return {
        # word: (short_hash, weight)
        word: (hash_value % CONTENT_SIZE, 1 if len(word) % 2 == 0 else -1)
        for word, hash_value in _KEYWORDS.items()
    }

_KEYWORDS_CACHE = _prepare_cache()