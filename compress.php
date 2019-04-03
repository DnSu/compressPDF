<?php
require_once ('./configActual.php');
if ($rcFolder) $srcFolder = './source';
if ($backupFolder) $backupFolder = './backup';
if ($age) $age = 365;

compress($srcFolder, $backupFolder, $age);

function compress($srcFolder, $backupFolder, $age) {
  $ageLimit = 86400 * $age; // 5 days

  $files = scandir($srcFolder);
  $ago = time() - filemtime($srcFolder);

  if ($ago > $ageLimit) {
    foreach($files as $file) {
      if ($file == '.' || $file == '..') continue;
      if(is_dir($srcFolder.'/'.$file)){
        
        // go down one level
        if(!is_dir($backupFolder.'/'.$file)) mkdir($backupFolder.'/'.$file);
        compress($srcFolder.'/'.$file, $backupFolder.'/'.$file, $age);

      } else {

        // compress file
        if(!is_file($backupFolder.'/'.$file)){
          if(preg_match('/(pdf|PDF)$/', $file)) {
            $dotPos = strrpos($srcFolder.'/'.$file, '.');
            $srcName = $srcFolder.'/'.$file;
            $backupName = $backupFolder.'/'.$file;
            $tmpName = substr($srcName, 0, $dotPos);
            $tmpName .= '_bk';
            $tmpName .= substr($srcName, $dotPos);
            $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.6 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile="'.$srcName.'" "'.$tmpName.'"';

            // make a backup copy
            copy($srcName, $backupName );

            if (is_file($backupName)) {
              // move original file to temp file
              rename($srcName, $tmpName);

              // compress the actual file
              system($cmd);

              // delete the temp file
              unlink($tmpName);
            }
          }
        }
      }
    }
  }


}