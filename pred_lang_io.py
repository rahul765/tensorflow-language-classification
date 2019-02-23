from LangPred import Predictor
from sklearn import metrics
import os
import warnings
from pathlib import Path
from Config import extension
from CheckFileUnicode import keep_utf8
from Config import _PATH_TRAIN, _PATH_TEST


def warn(*args, **kwargs):
    pass
warnings.warn = warn

'''
Returns the predicted language
'''
def predict(file):
    # lang in str
    myfile = open(file, encoding='utf-8', mode='r').read()
    lang = Predictor().language(myfile)
    return lang

'''
Extract and returns the suffix of file
'''
def suffix(file):
    ext = file.split(".")[-1]
    return ext

print (extension)

'''
Remove the files not in utf-8 format
'''
keep_utf8(_PATH_TRAIN)
keep_utf8(_PATH_TEST)


'''
Initialize the true and predicted language array
'''
lang_true = []
lang_pred = []

# # ==== Training ====
path = _PATH_TRAIN + '/'
predictor = Predictor()
predictor.learn(path)

# # ==== Prediction ====
path = _PATH_TEST + '/'
for root, dirs, files in os.walk(path):
    i = 0
    for file in files:
        i += 1
        print ("Entry:", file)
        #if i > 50:
        #    break
        temp_pred = predict(os.path.join(root, file))
        temp_true = suffix(file)
        lang_true.append(temp_true)
        lang_pred.append(temp_pred)
        print(file)
        print ("temp_pred:", temp_pred, "temp_true:", temp_true)


# ==== Prediction output ====
print("Predicted on", str(len(lang_pred)), "files. Results are as follows:")

result = metrics.confusion_matrix(lang_true, lang_pred)
print(result)

report = metrics.classification_report(lang_true, lang_pred)
print(report)

#=======Calssification Report======
with open("result.txt", "w") as resultfile:
    resultfile.write("Predicted on " + str(len(lang_pred)) + " files. Results are as follows:\n\n")
    resultfile.write("Confusion Matrix:\n")
    for row in result:
        string = ""
        for column in row:
            string += str(column) + "\t"
        resultfile.write(string+"\n")
    resultfile.write("\nClassification Report\n")
    resultfile.write(report)

#Writing the predicted result along with file name in language_predicted.txt
with open("language_predicted.txt","w") as pred_lang_file:
    pred_lang_file.write("File Name => Predicted File\n\n")
    pred_lang_file.writelines(map("{} => {}\n".format, files, lang_pred))
    