<html>
<body>
<br><br>
<center><h1>Restaurant Performance Evaluation Tool</h1>
<?php

class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('test.db');
    }
}

$db = new MyDB();
$result = $db->query("SELECT * FROM businesses order by name");

echo "<br><br><br><br><br><center><form action='detail.php' method='post'>";
echo '<select name = "id" size = 1>';
while($row = $result->fetchArray())
  {
	echo "<option value=" .$row['business_id'] . ">" . substr($row['name'] . ' - ' . $row['address'],0,50) . '...' . "</option>";
  }
echo "</select>";
echo "<br>";
echo "Start Date: <br> Day:<select name = 'sday' size = 1>";
for($i = 1; $i < 32; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "Month:<select name = 'smonth' size = 1>";
for($i = 1; $i < 13; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "Year:<select name = 'syear' size = 1>";
for($i = 2004; $i < 2015; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<br>";
echo "End Date: <br> Day:<select name = 'eday' size = 1>";
for($i = 1; $i < 32; $i++){

    echo "<option selected='selected' value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "Month:<select name = 'emonth' size = 1>";
for($i = 1; $i < 13; $i++){

    echo "<option selected='selected' value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "Year:<select name = 'eyear' size = 1>";
for($i = 2004; $i < 2015; $i++){

    echo "<option selected='selected' value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<br>Days of week to use:<br>";
echo 'Monday:<input type="checkbox" name="m" value="Monday" checked>';
echo ' Tuesday:<input type="checkbox" name="t" value="Tuesday" checked>';
echo ' Wednesday:<input type="checkbox" name="w" value="Wednesday" checked>';
echo ' Thursday:<input type="checkbox" name="th" value="Thursday" checked>';
echo ' Friday:<input type="checkbox" name="f" value="Friday" checked>';
echo ' Saturday:<input type="checkbox" name="s" value="Saturday" checked>';
echo ' Sunday:<input type="checkbox" name="su" value="Sunday" checked><br>';
echo "<input type='submit' />";
echo "</form>";
?>
</body>
</html>