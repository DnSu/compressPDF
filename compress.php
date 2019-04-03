<?php
require_once ('./configActual.php');
if ($rcFolder) $srcFolder = './source';
if ($backupFolder) $backupFolder = './backup';
if ($age) $age = 365;

compress($srcFolder, $backupFolder);

function compress($srcFolder, $backupFolder, $age) {
  //echo $srcFolder."\n";
  $files = scandir($srcFolder);
  // print_r($files);
  $ageLimit = 86400 * 0; // 5 days
  $ago = time() - filemtime($srcFolder);

  if ($ago > $ageLimit) {
    foreach($files as $file) {
      if ($file == '.' || $file == '..') continue;
      if(is_dir($srcFolder.'/'.$file)){
        if(!is_dir($backupFolder.'/'.$file)) mkdir($backupFolder.'/'.$file);
        compress($srcFolder.'/'.$file, $backupFolder.'/'.$file, $age);
      } else {
        if(!is_file($backupFolder.'/'.$file)){
          if(preg_match('/(pdf|PDF)$/', $file)) {
            $dotPos = strrpos($srcFolder.'/'.$file, '.');
            $srcName = $srcFolder.'/'.$file;
            $backupName = $backupFolder.'/'.$file;
            $tmpName = substr($srcName, 0, $dotPos);
            $tmpName .= '_bk';
            $tmpName .= substr($srcName, $dotPos);
            // echo $tmpName."\n";
            // echo $srcFolder.'/'.$file."\n";
            $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.6 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile="'.$srcName.'" "'.$tmpName.'"';
            // echo $cmd."\n";
            copy($srcName, $backupName );
            if (is_file($backupName)) {
              rename($srcName, $tmpName);
              system($cmd);
              unlink($tmpName);
            }
          }
        }
      }
    }
  }


}