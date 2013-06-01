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
> "Thanks for contacting the Office of the Secretary of State! We can find the voting information for your precinct."
- If ZIP code is not already saved for this session:
> "Please enter your address. Include your building or house number, your street name, and ZIP code."
- If ZIP code is already saved for this session:
> "Please enter your building or house number, and your street name."
- End session.

## Polling Search



