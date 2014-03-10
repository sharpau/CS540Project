<html>
<body>

<?php
$id = $_POST['id'];
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('test.db');
    }
}

$db = new MyDB();
$result = $db->query("SELECT * FROM schemastuff_businesses where id = '$id'");
while($row = $result->fetchArray())
  {
  echo $row['business_id'] . '<br>';
  echo $row['name'] . '<br>';
  echo $row['stars'] . '<br>';
  }
?>
</body>
</html>