# Manager’s credential information

Client number: 12345
Password: Zz!12345

# Other General Users' credential information

Client number: 1 (with only business account)
Client number: 2 (with both savings account and business account)
Client number: 3 (with only savings account)
Client number: 4 (no accounts, used for sending account request)
Password for all general users: Zz!11111

# KIT502 Assignment 2

This is the offical website of Secure Bank Pty. Ltd. Client number is provided only by bank staff. 

# Compatibility

This project contains 3 template files 'template.html', 'user_template.html', 'manager_template.html', which is not supported by Safari and Firefox, but it works perfectly on Chrome 'Version 72.0.3626.121 (Official Build) (64-bit)'.

# Functionality

Logical process

Login user validation included.

Users can only apply for either business account or savings account when sign up.

There are 6 tables involved in MySQL:
bank_user -- used to store access_type and client_number
bank_manager -- used to store bank manager's details
general_user -- used to store general users' detail information
business_account -- used to store business account information
savings_account -- used to store savings account information
statement -- used to store transaction history

General User's functions:
Can select statement history upon selected period
Can make payment for inter transfer, intra transfer and bills
Will record all transactions
Can view the demo video for how to use the website

Bank Manager's functions:
Can view transaction history for up to 3 months
Can add/remove any account directly from the table
Can change general users' access level
Can approve/deny general users' requests for opening account or transaction amount over $25000

Other minor functions are fully implemented

# Responsive

Responsive at screen width 600px.