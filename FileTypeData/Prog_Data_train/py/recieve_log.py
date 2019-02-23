#!/usr/bin/env python
import pika

connection = pika.BlockingConnection(pika.ConnectionParameters(host = 'localhost'))
channel = connection.channel()
channel.exchange_declare(exchange = 'logs', type = 'fanout'
    )
#result = channel.queue_declare(exclusive = True)
#queue_name = result.method.queue
channel.queue_declare(queue = 'C1', durable = True)

channel.queue_bind(exchange = 'logs', queue = 'C1')

print '[*] waiting for logs. to exit press CTRL + C'
def callback(ch, method, properties, body):
    print '[x] %r'%(body,)
channel.basic_consume(callback,
                      queue = 'C1',
                      no_ack = True
                      )
channel.start_consuming()
