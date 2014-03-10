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
$result = $db->query("SELECT * FROM schemastuff_businesses order by name");

echo "<br><center><form action='detail.php' method='post'>";
echo '<select name = "id" size = 1>';
while($row = $result->fetchArray())
  {
	echo "<option value=" .$row['id'] . ">" . $row['name'] . "</option>";
  }
echo "</select>";
echo "<input type='submit' />";
echo "</form>";
?>
</body>
</html>