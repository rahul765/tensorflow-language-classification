#!/usr/bin/env python
import pika
connection = pika.BlockingConnection(pika.ConnectionParameters(host = '127.0.0.1'))
channel = connection.channel()

#channel.queue_declare(queue = 'hello1')
print '[*] waiting for message. To exit press CTR + C'
def callback(ch, method, properties, body):
    print str(body)
    #print "[x] Recevied %r"%(body,)
channel.basic_consume(callback, queue = 'hello', no_ack =True)
channel.start_consuming()
