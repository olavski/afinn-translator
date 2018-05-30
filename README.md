# AFINN Translator
This package translates AFINN-165 list of english words to new languages.
These lists of words can be used to create multilingual sentiment analysis classifiers.

## About AFINN
"AFINN is a list of English words rated for valence with an integer between minus five (negative) and plus five (positive)."

More information about the AFINN list: http://www2.imm.dtu.dk/pubdb/views/publication_details.php?id=6010

## Installation
```bash
git clone

# download the english AFINN-165 json file
php download.php
```
## Usage
php translate.php [language]

Example:
```bash
# Translate AFINN to french
php translate.php fr
```
## Output
Three json files will be created in the /data/ directory

* afinn-{LANG}-new.json - list of unique non-english words
* afinn-{LANG}-same.json - list of words that translated to the same as the wnglish word
* afinn-{LANG}-combined.json - combined list of non-english and english words



## Resources
* https://github.com/words/afinn-165
* https://www.cs.uic.edu/~liub/FBS/sentiment-analysis.html#lexicon
