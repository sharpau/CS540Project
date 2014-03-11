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
$syear = $_POST['syear'];
$smonth = $_POST['smonth'];
$sday = $_POST['sday'];
if ($_POST['smonth'] < 10) $smonth = '0' .  $_POST['smonth'];
if ($_POST['sday'] < 10) $sday = '0' .  $_POST['sday'];
$start_date = $syear . '-' . $smonth . '-' . $sday;
$eyear = $_POST['eyear'];
$emonth = $_POST['emonth'];
$eday = $_POST['eday'];
if ($_POST['emonth'] < 10) $emonth = '0' .  $_POST['emonth'];
if ($_POST['eday'] < 10) $eday = '0' .  $_POST['eday'];
$end_date = $eyear . '-' . $emonth . '-' . $eday;
echo "Start Date: " . $_POST['sday'] . '-' . $_POST['smonth'] . '-' . $_POST['syear'] . '<br>';
echo "End Date: " . $_POST['eday'] . '-' . $_POST['emonth'] . '-' . $_POST['eyear'] . '<br>';
echo "You Chose: ";
if($_POST['m'] == 'Monday') echo "Monday, ";
if($_POST['t'] == 'Tuesday') echo "Tuesday, ";
if($_POST['w'] == 'Wednesday') echo "Wednesday, ";
if($_POST['th'] == 'Thursday') echo "Thursday, ";
if($_POST['f'] == 'Friday') echo "Friday, ";
if($_POST['s'] == 'Saturday') echo "Saturday, ";
if($_POST['su'] == 'Sunday') echo "Sunday ";
$days = array($_POST['m'], $_POST['t'],$_POST['w'],$_POST['th'],$_POST['f'],$_POST['s'],$_POST['su']);
$total_stars_friends = 0.;
$total_weight = 0.;
$stars = 0.;
$adj_stars = 0.;
$num = 0.;
$total_stars_friends = 0.;
$user_votes = 0.;
$user_votes_adj = 0.;
$vote_weight = 0.;
$cool_votes = 0.;
$cool_votes_adj = 0.;
$cool_weight = 0.;
$funny_votes = 0.;
$funny_votes_adj = 0.;
$funny_weight = 0.;
$useful_votes = 0.;
$useful_votes_adj = 0.;
$useful_weight = 0.;
$breakfast = 0.;
$breakfast_stars = 0.;
$breakfast_stars_adj = 0.;
$lunch = 0.;
$lunch_stars = 0.;
$lunch_stars_adj = 0.;
$dinner = 0.;
$dinner_stars = 0.;
$dinner_stars_adj = 0.;
$db = new MyDB();
$result = $db->query("SELECT * FROM reviews where business_id = '$id'");
while($row = $result->fetchArray())
  {
  if($row['date'] >= $start_date and $row['date'] <= $end_date and in_array($row['day'],$days)){
  $stars += $row['stars'];
  $num++;
  $adj_stars += $row['adj_stars'];
  $total_stars_friends += $row['stars'] * max(log($row['user_friends'],2),0);
  $total_stars_friends_adj += $row['adj_stars'] * max(log($row['user_friends'],2),0);
  $friends_weight += max(log($row['user_friends'],2),0);
  $user_votes += $row['stars'] * ($row['user_avg_votes']);
  $vote_weight += ($row['user_avg_votes']);
  $user_votes_adj += $row['adj_stars'] * ($row['user_avg_votes']);
  $cool_votes += $row['stars'] * ($row['cool_votes']+1);
  $cool_votes_adj += $row['adj_stars'] * ($row['cool_votes']+1);
  $cool_weight += ($row['cool_votes']+1);
  $funny_votes += $row['stars'] * ($row['funny_votes']+1);
  $funny_votes_adj += $row['adj_stars'] * ($row['funny_votes']+1);
  $funny_weight += ($row['funny_votes']+1);
  $useful_votes += $row['stars'] * ($row['useful_votes']+1);
  $useful_votes_adj += $row['adj_stars'] * ($row['useful_votes']+1);
  $useful_weight += ($row['useful_votes']+1);
  if($row['breakfast'] == '1'){
    $breakfast++;
	$breakfast_stars += $row['stars'];
	$breakfast_stars_adj += $row['adj_stars'];
  }
  if($row['lunch'] == '1'){
    $lunch++;
	$lunch_stars += $row['stars'];
	$lunch_stars_adj += $row['adj_stars'];
  }
  if($row['dinner'] == '1'){
    $dinner++;
	$dinner_stars += $row['stars'];
	$dinner_stars_adj += $row['adj_stars'];
  }
  }
  }
echo "<table border='1'>
<tr>
<th>Metric</th>
<th>Raw Stars</th>
<th>Stars vs. Average</th>
</tr>";
echo "<tr>";
echo "<td>" . "Unweighted" . "</td>";
echo "<td><center><span title='Sum of stars divided by number of reviews'>" . number_format($stars/$num,2) . "</span></td>";
echo "<td><center><span title='Sum of adjusted stars divided by number of reviews'>" . number_format($adj_stars/$num,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by user friends" . "</td>";
echo "<td><center><span title='Sum of products log2(friends) and stars all divided by sum of log2(friends)'>" . number_format($total_stars_friends/$friends_weight,2) . "</span></td>";
echo "<td><center><span title='Sum of products log2(friends) and adjusted stars all divided by sum of log2(friends)'>" . number_format($total_stars_friends_adj/$friends_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by user votes" . "</td>";
echo "<td><center><span title='Sum of products user average votes and stars all divided by sum of average votes'>" . number_format($user_votes/$vote_weight,2) . "</span></td>";
echo "<td><center><span title='Sum of products user average votes and adjusted stars all divided by sum of average votes'>" . number_format($user_votes_adj/$vote_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by review votes" . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Funny" . "</td>";
echo "<td><center><span title='Sum of products review funny votes and stars all divided by sum of funny votes'>" . number_format($funny_votes/$funny_weight,2) . "</span></td>";
echo "<td><center><span title='Sum of products review funny votes and adjusted stars all divided by sum of funny votes'>" . number_format($funny_votes_adj/$funny_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Useful" . "</td>";
echo "<td><center><span title='Sum of products review useful votes and stars all divided by sum of useful votes'>" . number_format($useful_votes/$useful_weight,2) . "</span></td>";
echo "<td><center><span title='Sum of products review useful votes and adjusted stars all divided by sum of useful votes'>" . number_format($useful_votes_adj/$useful_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Cool" . "</td>";
echo "<td><center><span title='Sum of products review cool votes and stars all divided by sum of cool votes'>" . number_format($cool_votes/$cool_weight,2) . "</span></td>";
echo "<td><center><span title='Sum of products review cool votes and adjusted stars all divided by sum of funny votes'>" . number_format($cool_votes_adj/$cool_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "By time of day" . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Breakfast" . "</td>";
echo "<td><center><span title='Sum of stars for breakfast divided by number of breakfasts'>" . number_format($breakfast_stars/$breakfast,2) . "</span></td>";
echo "<td><center><span title='Sum of adjusted stars for breakfast divided by number of breakfasts'>" . number_format($breakfast_stars_adj/$breakfast,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Lunch" . "</td>";
echo "<td><center><span title='Sum of stars for lunch divided by number of lunches'>" . number_format($lunch_stars/$lunch,2) . "</span></td>";
echo "<td><center><span title='Sum of adjusted stars for lunch divided by number of lunches'>" . number_format($lunch_stars_adj/$lunch,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Dinner" . "</td>";
echo "<td><center><span title='Sum of stars for dinner divided by number of dinners'>" . number_format($dinner_stars/$dinner,2) . "</span></td>";
echo "<td><center><span title='Sum of adjusted stars for dinner divided by number of dinners'>" . number_format($dinner_stars_adj/$dinner,2) . "</span></td>";
echo "</tr>";
echo "</table>";
?>
</body>
</html>