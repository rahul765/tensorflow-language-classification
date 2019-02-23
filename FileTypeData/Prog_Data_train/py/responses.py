from chatsenselib.variables import *

def getSensorValueAux(sensor):
    if (sensor['type'] == "sensor/temperature"):
        if (sensor['location'] == "sauna"):
            return(110);
        else:
            return(21);
    elif (sensor['type'] == "sensor/humidity"):
        return(80);
    else:
        return(5);
    
def getSensorValue(type,location):
    actualType = "sensor/" + type;
    for sensor in mSensorDB.all():
        if (sensor['type'] == actualType and sensor['location'] == location):
            return(getSensorValueAux(sensor));
        
def getAllScalarQuantities():
    mScalarQuantities = []
    for sensorType in mLangMapDB.all():
        mScalarQuantities.extend(sensorType['words'])
    return mScalarQuantities

def getAllLocations():
    mLocations = []
    for sensor in mSensorDB.all():
        mLocations.append(sensor['location'])
    return mLocations
