from constants import CONF_NAME



account_verification = ('Account verify','''Dear {name},\n Thank you for \
activating your account, we look forward to receiving your presentations. To \
complete the process please click on the following link to verify your email \
address {url}''')

password_reset = (		'Password reset','''Dear {name},\nPlease click on the \
following link to reset your password {url}''')

new_account = (			'Activate account','''Dear {name},\nYour account is ready \
for activation for the upcoming %s event, Please click on the following link \
to activate your account {url}''' % CONF_NAME)

recieved_presentation = ('Presentation received', '''Dear {name},\nCongratulations \
your presentation has uploaded successfully, to view your submission and confirm \
the upload please click <a href="{url}">here</a>''')
