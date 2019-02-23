""" Given an input string, reverse the string word by word.
    For example, Given s = "the sky is blue",
    return "blue is sky the".
    Python version in a line - bummer
"""

def reverseWords(str):
  """ First split the string with seperator as a space. Next reverse the resulting
	    list. There we use the extended slice operator ::-1. Finally join the
	    words in the reversed list using join
	"""
  print " ".join(str.split(' ')[::-1])

reverseWords("the sky is blue")