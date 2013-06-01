# Telephone Polling Place Finder

# New Message

## Process Message
- Sanitize input
	- Is there a zip code in the input?
	- If yes, then record zip code for this session, and continue.
	- Filter input by address and street.
		- Is there a postal address? If yes, then record postal address and continue.
		- Is there a street? If yes, then record street and exit to polling search.
	- If no, then switch to conversational zip entry.

## Conversational Zip Entry
> "Thanks for contacting the Office of the Secretary of State! We can find the voting information for your precinct."
- If zip code is not already saved for this session:
> "To begin, please send your zip code."
- If zip code is already saved for this session:
> "Please confirm your zip code. Is your zip code _____? Please reply with Y, or send your zip code."
	- Does message contains "Y"?
		- If yes, continue to street search.
		- If no, check input for zip code.
- Does input contain zip code?
		- If yes, record zip code for this session and exit to conversational street entry.
		- If no, display apology and loop this branch:
> "We're sorry but we cannot find a zip code in your response. It's usually a five-digit number. Please send your zip code."

## Conversational Street Entry
> "Okay, now we know your zip code is _____. To continue finding your polling place, reply with your postal address and street."
- Does message contain a string of numbers?
	- If yes, save string as postal address.
	- If no, display apology

## Polling Search



