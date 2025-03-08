<?php

// Query database for values
$action=$_GET["action"];

$db = mysqli_connect("localhost", "root", "", "books") or die(mysqli_error($db));

$sql = "describe books";
$result = mysqli_query($db, $sql);

switch ($action)
{
	case "add":
	case "update":
	case "delete":
		// ***** ADD *****
		// ***** UPDATE *****
		// ***** DELETE *****/
		if ("update" == $action)
		{
			$id = $_GET['id'];
			$sql = "SELECT * FROM books WHERE ID=$id";
			$oldData = mysqli_query($db, $sql);
			echo '<form id="newpart" action="update.php?redirect=index.php" method="POST" onsubmit="return validateForm()">';
			echo '<input type="hidden" value="'.$id.'" name="id" />';
			$oldRow = mysqli_fetch_row($oldData);
		}
		elseif ("delete" == $action)
		{
			$id = $_GET['id'];
			$sql = "delete FROM books WHERE ID=$id";
			mysqli_query($db, $sql);

			$action = "browse";
		}	
		elseif ( "add" == $action)
		{
			echo '<form onsubmit="return false;">';
			echo '<label for="isbnnum">Enter an ISBN number:<br/></label>';
			echo '<input id="isbnnum" type="text" /><br />';
			echo '<input type="submit" value="Get Info" onclick="fillPage(\'getInfo\',0);" />';
			echo '</form>';
			echo '<form id="newpart" action="add.php?redirect=index.php" method="POST" onsubmit="return validateForm()">';
		}
        elseif ( "search" == $action)
        {
            echo '<form onsubmit="return false;">';
            echo '<label for="author_fn">Enter author first and last names:<br/></label>';
            echo '<input id="author_fn" type="text" />';
            echo '<input id="author_ln" type="text" /><br />';
            echo '<input type="submit" value="Get Info" onclick="fillPage(\'getInfo\',0);" />';
            echo '</form>';
            echo '<form id="newpart" action="search_author.php?redirect=index.php" method="POST" onsubmit="return validateForm()">';
        }
		
		if ( "add" == $action || "update" == $action )
		{
			echo "<table>";
			echo "<tbody>";
			$columnNumber = 0;
			while ($row = mysqli_fetch_row($result))
			{
			    if ( $row[0] != "ID" )
			    {
               echo "<tr>";
               echo "<td>".$row[0]."</td>";
               echo '<td>';
               // Print either an empty input field or a pre-filled one
               if ("update" == $action)
               {
                  if ( $row[0] == "Description" )
                  {
                     echo '<textarea name="' . htmlspecialchars($row[0]) . '" rows="5" cols="80" wrap="physical">' . $oldRow[$columnNumber] . '</textarea>';
                  }
                  else
                  {
                    echo '<input name="' . htmlspecialchars($row[0]) . '" type="text" value="' . htmlentities($oldRow[$columnNumber]) . '" size="75"/>';
                  }
               }
               else // Add - Get ISBN info if set
               {				         
                  if ( $row[0] == "Description" )
                  {
                     echo '<textarea name="' . htmlentities($row[0]) . '" rows="5" cols="80" wrap="physical"></textarea>';
                   }
                   else
                   {
                      echo '<input name="' . htmlentities($row[0]) . '" type="text" size="75"/>';
                   }
               }
               echo "</td>";
				   echo "</tr>";
			   }
			   $columnNumber += 1;
			}

			echo "</tbody>";
			echo "</table>";
			echo '<input type="submit" />';
			echo '<input type="button" value="Cancel" OnClick="parent.location=\'./index.php\';" />';
			echo "</form>";
			// Client will execute JavaScript that follows a '-----' marker
			echo '-----
			';
			  break;
	    }
       elseif ( "search" == $action )
		 {
			echo "<table>";
			echo "<tbody>";
         while ($row = mysqli_fetch_row($result))
         {
            if ( $row[0] != "ID" )
            {
               echo "<tr>";
               echo "<td>".$row[0]."</td>";
               echo '<td>';
               // Print either an empty input field or a pre-filled one
               if ( $row[0] == "Description" )
               {
                  echo '<textarea name="' . htmlentities($row[0]) . '" rows="5" cols="80" wrap="physical"></textarea>';
               }
               else
               {
                  echo '<input name="' . htmlentities($row[0]) . '" type="text" size="75"/>';
               }
            }
            echo "</td>";
            echo "</tr>";
         }
          
			echo "</tbody>";
			echo "</table>";
			echo '<input type="submit" />';
			echo '<input type="button" value="Cancel" OnClick="parent.location=\'./index.php\';" />';
			echo "</form>";
			// Client will execute JavaScript that follows a '-----' marker
			echo '-----
			';
         break;
      }

	case "browse":
		// ***** BROWSE *****
		$nextQuery = "SELECT ID";
		echo '<table class="example table-autosort:0 table-stripeclass:alternate table-autofilter table-autopage:20 table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount">';
		echo "<thead>";
		echo "<tr>";
		// First Column is the delete link
		echo '<th>Delete</th>';
		// Second column is an edit link
		echo '<th>Modify</th>';

		while ($row = mysqli_fetch_row($result))
		{
		   if ("ID" != $row[0])
		   {
			  $nextQuery .= ", `".$row[0]."`";
			  echo '<th class="table-sortable:default table-filterable">'.$row[0]."</th>";
		   }
		}   

		echo "</tr>";
		echo "</thead>";
		
		$nextQuery .= " FROM books order by author, title";
		$result = mysqli_query($db, $nextQuery);
		echo "<tbody>";
		while ($row = mysqli_fetch_row($result))
		{		    
			echo "<tr id='id_tablerow_$row[0]' onmouseover=\"TableRow.onmouseover(this,$row[0]);\" onmouseout=\"TableRow.onmouseout(this,$row[0]);\" onclick=\"TableRow.onclick(this,$row[0]);\">";
			// First column is a delete link
			echo '<td class="nopadding" align="center"><img src="images/delete-icon.gif" title="delete" onclick="fillPage(\'delete\','.$row[0].');" style="cursor:hand;"/></td>';
			// Second column is an modify link
			echo '<td class="nopadding" align="center"><img src="images/edit-icon.gif" title="edit" onclick="fillPage(\'update\','.$row[0].');" style="cursor:hand;"/></td>';
			for ($col = 1; $col < count($row); $col++)
			{
				echo "<td>".htmlentities(substr($row[$col],0, 50))."</td>";
			}
			echo "</tr>";
		}
		echo "</tbody>";
		echo "<tfoot>";
		echo "<tr>";
		echo '<td colspan=1 class="table-page:previous" style="cursor:pointer;"><img src="images/previous.png" title="Previous"/> Previous</td>';
		echo '<td style="text-align:center;">Page <span id="t1page"></span>&nbsp;of <span id="t1pages"></span></td>';
		echo '<td class="table-page:next" style="cursor:pointer;">Next <img src="images/next.png" title="Next"/></td>';
        echo '<td colspan=6></td>';
		echo "</tr>";
		echo '<td colspan=9><span id="t1filtercount"></span> of <span id="t1allcount"></span>&nbsp;rows match filter(s)</td>';
		echo "<tr>";
		echo "</tr>";
		echo "</tfoot>";
		echo "</table>";
		// Client will execute JavaScript that follows a '-----' marker
		echo '-----
		// Set up table sorting, etc.
		Table.auto();
		';
	break;
}
mysqli_close($db);

?>