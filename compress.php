<?php
$backupFolder = './backup';
iterFolder('./source', $backupFolder);

function iterFolder($folder, $backupFolder) {
  //echo $folder."\n";
  $files = scandir($folder);
  // print_r($files);
  $ageLimit = 86400 * 0; // 5 days
  $ago = time() - filemtime($folder);
  if ($ago > $ageLimit) {
    foreach($files as $file) {
      if ($file == '.' || $file == '..') continue;
      if(is_dir($folder.'/'.$file)){
        if(!is_dir($backupFolder.'/'.$file)) mkdir($backupFolder.'/'.$file);
        iterFolder($folder.'/'.$file, $backupFolder.'/'.$file);
      } else {
        if(!is_file($backupFolder.'/'.$file)){
          if(preg_match('/(pdf|PDF)$/', $file)) {
            copy($folder.'/'.$file, $backupFolder.'/'.$file );
            $dotPos = strrpos($folder.'/'.$file, '.');
            // echo $dotPos;
            $srcName = $folder.'/'.$file;
            $tmpName = substr($srcName, 0, $dotPos);
            $tmpName .= '_bk';
            $tmpName .= substr($srcName, $dotPos);
            // echo $tmpName."\n";
            rename($srcName, $tmpName);
            // echo $folder.'/'.$file."\n";
            $cmd = 'gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.6 -dPDFSETTINGS=/ebook -dNOPAUSE -dQUIET -dBATCH -sOutputFile="'.$srcName.'" "'.$tmpName.'"';
            echo $cmd."\n";
            system($cmd);
            unlink($tmpName);
          }
        }
      }
    }
  }


}