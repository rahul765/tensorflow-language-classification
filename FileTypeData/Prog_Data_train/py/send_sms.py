from twilio.rest import TwilioRestClient

def sendText():
    # Get these crednetials from http://twilio.com/user/account
    account_sid = "AC7876702367633387f88bd20f5a83008c"
    auth_token = "645c7c2162a2f362c6a8a0c1b939fe45"
    client = TwilioRestClient(account_sid, auth_token)

    # Make the call
    msg = client.sms.messages.create(to = "+15109821323",  # Any phone number
                                     from_ = "+15106296982",  # Valid Twilio number
                                     body = "Hello there!")

