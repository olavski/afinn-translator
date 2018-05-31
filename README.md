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
### Translation Output
Two tsv files will be created in the /data/{LANG} directory

* translated.json - list of unique non-english words
* en-untranslated.tsv - list of english words that could not be translated


## Build
Build compiles all .tsv files in /data/{LANG} directory into one single file

it will start with 
* translated.tsv

and then go through every other .tsv file in that diretory and merge the lists.
If a word exists in several files the weight will be overwritten with the weight of the last occurrance of the word.

Example:
```bash
# Merge all .tsv files into one
php build.php no
```
The compiled files can be found in /data/{lang}/build/{lang}.tsv


## Watch
Running the watcher will recompile the final .tsv file every time one of the files in /data/{LANG} is updated.
This is useful is you are curating lists, and using the compiled .tsv in a classifier while testing.

Example:
```bash
# Watch for changes in .tsv and recompile
php watch.php no
```


## Resources
* https://github.com/words/afinn-165
* https://www.cs.uic.edu/~liub/FBS/sentiment-analysis.html#lexicon
