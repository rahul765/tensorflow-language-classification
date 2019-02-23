
import nltk
sentence = """What is the temperature in the living room?"""
tokens = nltk.word_tokenize(sentence)
tagged = nltk.pos_tag(tokens)
entities = nltk.chunk.ne_chunk(tagged)
from nltk.corpus import treebank
t = treebank.parsed_sents('wsj_0001.mrg')[0]
# t.draw()
