import spacy;
from chatsenselib.responses import getAllScalarQuantities, getAllLocations

def isscalarquantity(sr,w):
    if w in getAllScalarQuantities():
        return(True);
    else:
        return(False);
        
def matchscalarquantity(sr,s):
    word = s["lemma"].lower()
    if (s["POS_fine"] == "NN" and
        isscalarquantity(sr,word)):
        sr["quantity"] = word
        return(True);
    else:
        return(False);
    
