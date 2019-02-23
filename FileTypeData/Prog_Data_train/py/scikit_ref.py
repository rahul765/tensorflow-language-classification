from data.dataframes import load_iris
from sklearn import tree
from sklearn.cross_validation import train_test_split
from sklearn.metrics import accuracy_score

if __name__ == '__main__':
    iris = load_iris()

    X = iris[[f for f in iris.columns if f != 'class']]
    y = iris['class']
    X_train, X_test, y_train, y_test = train_test_split(X, y)

    clf = tree.DecisionTreeClassifier().fit(X_train, y_train)
    prediction = clf.predict(X_test)

    print(accuracy_score(y_test, prediction)) # 0.868421052632