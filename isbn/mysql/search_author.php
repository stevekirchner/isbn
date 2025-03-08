<html>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="table.css" />
<script type="text/javascript" language="javascript" src="javascript.js"></script>
<script type="text/javascript" language="javascript" src="table.js"></script>

<?php

// get first/last name
$author_fn=strtolower($_GET['author_fn']);
$author_ln=strtolower($_GET['author_ln']);

    
if ( isset ($author_fn) && isset($author_ln) )
{
   echo '<div>';
   echo '<table class="example table-autosort:0 table-stripeclass:alternate table-autofilter table-autopage:20 table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount">';
   echo "<thead>";
   echo "<tr>";

   // First Column is the select checkbox
   echo '<th class="nopadding">Select</th>';
   echo '<th class="table-sortable:default table-filterable">Title</th>';
   echo '<th class="table-sortable:default table-filterable">ISBN</th>';
   echo '<th class="table-sortable:default table-filterable">Description</th>';

   echo "</tr>";
   echo "</thead>";

   echo "<tbody>";

   $author_name = "$author_fn+$author_ln";
   $url="https://www.googleapis.com/books/v1/volumes?q=inauthor:\"$author_name\"&maxResults=40";
   $page = file_get_contents($url);
   $data = json_decode($page, true);

    //  At least one result
    $book_number = 0;

   if ( $data['totalItems'] == 0 )
   {
      echo "<h2>Author not found.</h2>";
   }
   else
   { 
      foreach($data['items'] as $item)
      {
        echo "<tr id='id_tablerow_$book_number' onmouseover=\"TableRow.onmouseover(this,$book_number);\" onmouseout=\"TableRow.onmouseout(this,$book_number);\" onclick=\"TableRow.onclick(this,$book_number);\">";
        // First column is a checkbox
        echo '<td class="nopadding" align="center"><input type="checkbox" style="cursor:hand;"/></td>';
        echo "<td>" . htmlentities($item['volumeInfo']['title']) . "</td>";
        $volume_info = $item['volumeInfo'];
        if (array_key_exists('industryIdentifiers', $volume_info))
        {
            if ($volume_info['industryIdentifiers'][0]['type'] == "ISBN_13")
            {
               echo "<td>" .  htmlentities($volume_info['industryIdentifiers'][0]['identifier']) . "</td>";
            }
            else if ($volume_info['industryIdentifiers'][1]['type'] == "ISBN_13")
            {
                 echo "<td>" . htmlentities($volume_info['industryIdentifiers'][1]['identifier']) . "</td>";
            }
            else # Use the ISBN 10 or whatever is in index 0
            {
                 echo "<td>" .  htmlentities($volume_info['industryIdentifiers'][0]['identifier']) . "</td>";
            }

         }
         echo "<td>" . htmlentities(substr($volume_info['description'], 0, 60)) . "</td>";
         echo "</tr>";
         $book_number += 1;
      }
   }
   echo "</tbody>";
   echo "<tfoot>";
   echo "<tr>";
   echo '<td colspan=2 class="table-page:previous" style="cursor:pointer;"><img src="images/previous.png" title="Previous"/> Previous</td>';
   echo '<td style="text-align:center;">Page <span id="t1page"></span>&nbsp;of <span id="t1pages"></span></td>';
   echo '<td class="table-page:next" style="cursor:pointer;">Next <img src="images/next.png" title="Next"/></td>';
   echo "</tr>";
   echo '<td colspan=4><span id="t1filtercount"></span> of <span id="t1allcount"></span>&nbsp;rows match filter(s)</td>';
   echo "<tr>";
   echo "</tr>";
   echo "</tfoot>";
   echo "</table>";
   echo "</div>";
}
else
{
   echo "Search parameters not set<br/>";
}
?>
</html>