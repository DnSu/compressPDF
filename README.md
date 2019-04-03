# compressPDF
* iterate through source folder
* backup and compresses PDF
  * copy file to backup folder
  * rename file with temporary file name
  * compress file back into the old name
  * delete temporary file

# Install
* make a copy of config.php and call it configActual.php
* update the following values in configActual.php
  * srcFolder - folder where you have PDF that you want to compress
  * backupFolder - where to keep a copy of the original
  * age - how old the file must be in order to compress it (measured in days)
* dependencies
  * ghostscript

# Run
`php compress.php`
