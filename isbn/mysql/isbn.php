<?php
header('Content-Type: text/plain; charset=utf-8');

$isbn=$_GET['isbn'];

$outstring = "";
   
if(isset($isbn))
{
   $url="https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
   $page = file_get_contents($url);
   $data = json_decode($page, true);
      
   if ( $data['totalItems'] == 0 )
   {
      $outstring = "ISBN not found.";
   }
   else
   {
      $item = $data['items'][0];
      $outstring .= "Title|" . ucwords($item['volumeInfo']['title']) . PHP_EOL;
      $outstring .= "Author|";
      foreach( $item['volumeInfo']['authors'] as $author )
      {
         $outstring .= "$author / ";
      }
      $outstring = substr($outstring, 0, -3) . PHP_EOL;
      $outstring .= "ISBN|" . $isbn . PHP_EOL;
      $outstring .= "Description|" . $item['volumeInfo']['description'] . PHP_EOL;
   }
}
echo $outstring;
?>