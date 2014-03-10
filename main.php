<html>
<body>
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

echo "<br><center><form action='detail.php' method='post'>";
echo '<select name = "id" size = 1>';
while($row = $result->fetchArray())
  {
	echo "<option value=" .$row['business_id'] . ">" . $row['name'] . "</option>";
  }
echo "</select>";
echo "<br>";
echo "Start Date: <select name = 'sday' size = 1>";
for($i = 1; $i < 32; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<select name = 'smonth' size = 1>";
for($i = 1; $i < 13; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<select name = 'syear' size = 1>";
for($i = 2004; $i < 2015; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<br>";
echo "End Date: <select name = 'eday' size = 1>";
for($i = 1; $i < 32; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<select name = 'emonth' size = 1>";
for($i = 1; $i < 13; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<select name = 'eyear' size = 1>";
for($i = 2004; $i < 2015; $i++){

    echo "<option value=" . $i . ">" . $i . "</option>";
	 
}
echo "</select>";
echo "<br>";
echo 'Monday:<input type="checkbox" name="m" value="Monday">';
echo ' Tuesday:<input type="checkbox" name="t" value="Tuesday">';
echo ' Wednesday:<input type="checkbox" name="w" value="Wednesday">';
echo ' Thursday:<input type="checkbox" name="th" value="Thursday">';
echo ' Friday:<input type="checkbox" name="f" value="Friday">';
echo ' Saturday:<input type="checkbox" name="s" value="Saturday">';
echo ' Sunday:<input type="checkbox" name="su" value="Sunday"><br>';
echo "<input type='submit' />";
echo "</form>";
?>
</body>
</html>