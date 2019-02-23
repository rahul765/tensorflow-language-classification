import serial;

ser = serial.Serial('/dev/tty.usbmodemfd121', 115200)
ser.write(bytes('D','UTF-8'))

