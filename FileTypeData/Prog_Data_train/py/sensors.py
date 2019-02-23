from chatsenselib.variables import mSensorDB, mLangMapDB

def addSensor(mSensorID, mSensorType, mSensorLoc):
    mSensorDB.insert({
        "id": mSensorID,
        "type": mSensorType,
        "location": mSensorLoc
    })

# Adds Language Mapping entry into the DB
"""
mSensorType => "sensor/temperature" <string>
mKeywords => ["temperature", "heat", "hot", "cold", "warm"] <list>
mOperations => [{
    "what": "getting the value of a sensor", <string>
    "verbs": ["get", "give", "show"], <list>
    "subjects": ["value", ""] # Can be used by humans as in "Get the *value* of xyz" <list>
    }<dict>] <list>
"""
def addLangMap(mSensorType, mKeywords, mOperations):
    mLangMapDB.insert({
        "type": mSensorType,
        "words": mKeywords,
        "operations": mOperations
    })
