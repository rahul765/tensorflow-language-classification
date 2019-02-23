import spacy
from chatsenselib.parser1 import *
from chatsenselib.variables import *

nlp = spacy.load('en')

my_question = "";
first = True;

print("");

try:
    while (my_question != "quit"):
        if (first):
            my_question = unicode(raw_input("Bot: How can I help you?\nYou: "),"utf-8")
        else:
            my_question = unicode(raw_input("You: "),"utf-8")
	first = False
        if (my_question == "quit"):
            print("\nBot: Bye!");
        else:
            doc = nlp(my_question)
            response = processrequest(doc)
            print("Bot: " + response +"\n")
except EOFError:
    print("Bot: Thank you, bye bye!");



            
