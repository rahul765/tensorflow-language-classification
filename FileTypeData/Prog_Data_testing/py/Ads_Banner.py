import pandas as pd
# from sklearn.tree import DecisionTreeClassifier
from sklearn.cross_validation import train_test_split
from sklearn.metrics.metrics import classification_report
from sklearn.pipeline import Pipeline
from sklearn.grid_search import GridSearchCV
from sklearn.ensemble import RandomForestClassifier

if __name__ == '__main__':
	
	#Read the csv file using pandas
	df = pd.read_csv('ad-dataset/ad.data', header=None)
	features_columns = set(df.columns.values)
	labels_column = df[len(df.columns.values)-1]
	
	#The last column describes the targets
	features_columns.remove(len(df.columns.values)-1)
	
	#Encode the ads as positive(1) and the content as negative(0)
	Labels = [1 if e=='ad.' else 0 for e in labels_column]
	Features = df[list(features_columns)]
	
	#Some instances are missing some values for the image's dimensions.
	#These missing values are marked by whitespace and a question mark.
	#Replace the missing values with -1.
	Features.replace(to_replace=' *\?', value=-1, regex=True, inplace=True)

	#Split the data into training and test sets
	Features_train, Features_test, Labels_train, Labels_test = train_test_split(Features, Labels)

	#Create a pipeline and an instance of DecisionTreeClassifier for grid search.
	#Set 'criterion' to 'entropy' to build the tree using the information gain heuristic.
	# pipeline = Pipeline([('clf', DecisionTreeClassifier(criterion='entropy'))])
	#Replace Decision Tree with Random Forest
	pipeline = Pipeline([('clf', RandomForestClassifier(criterion='entropy'))])

	#Specify the hyperparameter space for grid search
	parameters = {
	'clf__n_estimators' : (5, 10, 20, 50),
	'clf__max_depth' : (50, 150, 250),
	'clf__min_samples_split' : (1, 2, 3),
	'clf__min_samples_leaf' : (1, 2, 3)
	}

	#Set GridSearchCV() to maximize the model's F1 score
	grid_search = GridSearchCV(pipeline, parameters, n_jobs=-1, verbose=1, scoring='f1')
	grid_search.fit(Features_train, Labels_train)
	print 'Best score: %0.3f' %grid_search.best_score_
	print 'Best parameters set:'
	best_parameters = grid_search.best_estimator_.get_params()
	for param_name in sorted(parameters.keys()):
		print '\t%s %r' %(param_name, best_parameters[param_name])

	predictions = grid_search.predict(Features_test)
	print classification_report(Labels_test, predictions)

