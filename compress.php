<?php
require_once ('./configActual.php');
if ($rcFolder) $srcFolder = './source';
if ($backupFolder) $backupFolder = './backup';

iterFolder($srcFolder, $backupFolder);

function iterFolder($srcFolder, $backupFolder) {
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
        iterFolder($srcFolder.'/'.$file, $backupFolder.'/'.$file);
      } else {
        if(!is_file($backupFolder.'/'.$file)){
          if(preg_match('/(pdf|PDF)$/', $file)) {
            $dotPos = strrpos($srcFolder.'/'.$file, '.');
            $srcName = $srcFolder.'/'.$file;
            $tmpName = substr($srcName, 0, $dotPos);
            $tmpName .= '_bk';
            $tmpName .= substr($srcName, $dotPos);
            // echo $tmpName."\n";
            // echo $srcFolder.'/'.$file."\n";
            $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.6 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile="'.$srcName.'" "'.$tmpName.'"';
            echo $cmd."\n";
            // copy($srcName, $backupFolder.'/'.$file );
            // rename($srcName, $tmpName);
            // system($cmd);
            // unlink($tmpName);
          }
        }
      }
    }
  }


}