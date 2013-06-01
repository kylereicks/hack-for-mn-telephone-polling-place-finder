# Telephone Polling Place Finder

# New Message

## Process Message
- Sanitize input
	- Is there a five-digit ZIP code in the input?
	- If yes, then record ZIP code for this session, and continue.
	- Filter input by address and street name.
		- Detecting a house number? If yes, then record house number and continue.
		- Detecting a street name? If yes, then record street name and exit to polling search.
		- If no, then switch to conversational address entry.

## Conversational Address Entry
> "Thanks for contacting the Minnesota Office of the Secretary of State! We can find the polling place information for your precinct."
- If ZIP code is not already saved for this session:
> "Please enter your address. Include your house number or building number, your street name, and ZIP code."
- If ZIP code is already saved for this session:
> "Please enter your building or house number, and your street name."
- End session.

## Polling Search
- Search database for ZIP code.
	- If ZIP code exists in the database, continue to street name search.
	- If ZIP code does not exist in the database, display error and end session.
	> "There is no information for this ZIP code! Please check your postal address information and try again."
- Search database for street name.
	- If street name exists in the database, continue to house number search.
	- If street name does not exist in the database, display error and end session.
	> "The street name you entered does not exist in your ZIP code. Please check your postal address information and try again."
- Search database for house number.
	- If house number exists in the database, exit to polling place information.
	- If house number does not exist in the database, display error.
	> "The house number and street combination which you have selected do not exist. Please call us at 651-215-1440 (Metro Area) or 1-877-600-VOTE (Greater MN) and we'll try to help."