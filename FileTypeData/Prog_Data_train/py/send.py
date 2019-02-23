#!/usr/bin/env python
import pika

connection = pika.BlockingConnection(pika.ConnectionParameters(host ="127.0.0.1"))
channel = connection.channel()
#channel.queue_declare(queue = 'hello1')
channel.basic_publish(exchange = '', routing_key = 'hello',body = 'hello world!')
print '[x] sent message hello world'
connection.close()
