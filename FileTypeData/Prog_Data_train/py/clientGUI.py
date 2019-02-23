#-*-coding:utf-8-*- 
import easygui as eg
import socket
import sys
reload(sys) 
sys.setdefaultencoding('utf-8')

PORT=50000
HOST=eg.enterbox("What is your server IP")

while(True):
	#data=" ".join(sys.argv[1:])
	#data=raw_input("Please input your instruction:")
	msg         = "Enter your HTTP instruction.The server support 'ls' 'add' 'delete' 'modify' "
	title       = "GchHTTP Network communication"
	fieldNames  = ["Instruction","StudentID","StudentName","StudentPicture"]
	fieldValues = []  # we start with blanks for the values
	fieldValues = eg.multenterbox(msg,title, fieldNames)
	data=fieldValues[0]+' '+fieldValues[1]+' '+fieldValues[2]+' '+fieldValues[3]

	sock=socket.socket(socket.AF_INET,socket.SOCK_STREAM)
	try:
		sock.connect((HOST,PORT))
		sock.sendall(data)
		received=sock.recv(1024)
	finally:
		sock.close()
	#print "Sent: {}".format(data)
	#print "Received: {}".format(received)
	eg.codebox("Received from server:" , "GchHTTP response", received)
	#if data=="quit":
