'''

Pig Latin Translator

by Dinesh Singh

'''

def translate():

    pig = 'ay'
    print "Welcome to Dinesh's Pig Latin Translator!\n"

    while True:
        original = raw_input('Please enter a word: ')
        word = original.lower()
        first = word[0]
        new_word = word + first + pig
        new_word = new_word[1:len(new_word)]
        if len(original) > 0 and original.isalpha():
            print "Your translated word is: "+ new_word + "\n"
        else:
            print 'Invalid Entry'

translate()