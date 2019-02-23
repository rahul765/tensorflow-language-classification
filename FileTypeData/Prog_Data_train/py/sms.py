from twilio.rest import Client

# account sid and auth token
account_sid = ''
auth_token = ''
client = Client(account_sid, auth_token)

message = client.messages.create(
    body='Hey there! this is a test',
    from_='+15555555555', # replace with twilio number
    to='+12316851234' # replace with your number
)

print(message.sid)

