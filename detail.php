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
$total_stars = 0;
$total_weight = 0;
$db = new MyDB();
$result = $db->query("SELECT * FROM reviews where business_id = '$id'");
while($row = $result->fetchArray())
  {
  $total_stars += $row['adj_stars'] * max(log($row['user_friends'],2),0);
  $total_weight += max(log($row['user_friends'],2),0);
  }
echo 'Stars weighted by friends: ' . $total_stars/$total_weight;
?>
</body>
</html>