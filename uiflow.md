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
- If ZIP code is not already saved for this session, return response and end session:
> "Please enter your address. Include your house number or building number, your street name, and ZIP code."
- If ZIP code is already saved for this session:
> "Please enter your building or house number, and your street name."
	- Filter input by address and street name.
		- Detecting a house number? If yes, then record house number and continue.
		- Detecting a street name? If yes, then record street name and exit to polling search.


## Polling Search
- Search database for ZIP code.
	- If ZIP code exists in the database, continue to street name search.
	- If ZIP code does not exist in the database, display error and end session.
	> "There is no information for this ZIP code! Please check your postal address information and try again."
- Search database for street name.
	- If street name exists in the database, check whether street name exists in ZIP code.
	- If street address exists in ZIP code, continue to house number search.
	- If street name does not exist in the database, display error and end session.
	> "The street name you entered does not exist in your ZIP code. Please call us at 651-215-1440 (Metro Area) or 1-877-600-VOTE (Greater MN) and we'll try to help."
- Search database for house number.
	- If house number does not exist in street data, exit to house number search.
	- If house number exists in street data, exit to polling place information.

## House Number Search
> "We aren't sure what your house number is. Here are the options for [Street Name]."
- Assemble response ranges based on data for street name. The folowing example is based on a search for ZIP 55414, street University Ave SE.
> "Reply 1 if your house number range is 13-327."
> "Reply 2 if your house number range is 330-410 and an even number."
> "Reply 3 if your house number range is 401-425 and an odd number."
> "Reply 4 if your house number range is 504-818."
> "Reply 5 if your house number range is 915-1029."
> "Reply 6 if your house number range is 1101-1307 and an odd number."
> "Reply 7 if your house number range is 1501-2218."
> "Reply 8 if your house number range is 2221-3430."
- Wait for response for this session.
	- If response is within reply range, exit to polling place information.
	- If response is not within reply range, display error and end session.
> "Your response did not contain a response to match the house number ranges for your street. Please call us at 651-215-1440 (Metro Area) or 1-877-600-VOTE (Greater MN) and we'll try to help."

## Polling Place Information


