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
$result = $db->query("SELECT name FROM businesses where business_id = '$id'");
$bname = $result->fetchArray();
echo '<br><br><br><center><h1>' . $bname['name'] . '</h1>' . '<br>';
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
$dinner_stars_adj = 0.;$result = $db->query("SELECT * FROM reviews where business_id = '$id'");

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
  echo "<center>";
echo "<br><table border='1'>
<tr>
<th>Metric</th>
<th>Raw Stars</th>
<th>Stars vs. Average</th>
</tr>";
echo "<tr>";
echo "<td>" . "Unweighted" . "</td>";
echo "<td><span title='Average star rating of reviews'><center>" . number_format($stars/$num,2) . "</span></td>";
echo "<td><span title='Average of difference between review rating and that average rating of that user.'><center>" . number_format($adj_stars/$num,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by user friends" . "</td>";
echo "<td><span title='Weighted average of stars, weighted by log2(friends of user).'><center>" . number_format($total_stars_friends/$friends_weight,2) . "</span></td>";
echo "<td><span title='Weighted average of adjusted stars, weighted by log2(friends of user).'><center>" . number_format($total_stars_friends_adj/$friends_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by user votes" . "</td>";
echo "<td><span title='Weighted average of stars, weighted by the average number of votes on this user's reviews.'><center>" . number_format($user_votes/$vote_weight,2) . "</span></td>";
echo "<td><span title='Weighted average of adjusted stars, weighted by the average number of votes on this user's reviews.'><center>" . number_format($user_votes_adj/$vote_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "Weighted by review votes" . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Funny" . "</td>";
echo "<td><span title='Weighted average of stars, weighted by the number of funny votes on the review.'><center>" . number_format($funny_votes/$funny_weight,2) . "</span></td>";
echo "<td><span title='Weighted average of adjusted stars, weighted by the number of funny votes on the review.'><center>" . number_format($funny_votes_adj/$funny_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Useful" . "</td>";
echo "<td><span title='Weighted average of stars, weighted by the number of useful votes on the review.'><center>" . number_format($useful_votes/$useful_weight,2) . "</span></td>";
echo "<td><span title='Weighted average of adjusted stars, weighted by the number of useful votes on the review.'><center>" . number_format($useful_votes_adj/$useful_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Cool" . "</td>";
echo "<td><span title='Weighted average of stars, weighted by the number of cool votes on the review.'><center>" . number_format($cool_votes/$cool_weight,2) . "</span></td>";
echo "<td><span title='Weighted average of adjusted stars, weighted by the number of cool votes on the review.'><center>" . number_format($cool_votes_adj/$cool_weight,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td>" . "By time of day" . "</td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Breakfast" . "</td>";
echo "<td><span title='Average stars for reviews that mention breakfast or morning.'><center>" . number_format($breakfast_stars/$breakfast,2) . "</span></td>";
echo "<td><span title='Average adjusted stars for reviews that mention breakfast or morning.'><center>" . number_format($breakfast_stars_adj/$breakfast,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Lunch" . "</td>";
echo "<td><span title='Average stars for reviews that mention lunch, afternoon, or midday.'><center>" . number_format($lunch_stars/$lunch,2) . "</span></td>";
echo "<td><span title='Average adjusted stars for reviews that mention lunch, afternoon or midday.'><center>" . number_format($lunch_stars_adj/$lunch,2) . "</span></td>";
echo "</tr>";
echo "<tr>";
echo "<td><center>" . "Dinner" . "</td>";
echo "<td><span title='Average stars for reviews that mention dinner, evening, drinks or night.'><center>" . number_format($dinner_stars/$dinner,2) . "</span></td>";
echo "<td><span title='Average adjusted stars for reviews that mention dinner, evening, drinks or night.'><center>" . number_format($dinner_stars_adj/$dinner,2) . "</span></td>";
echo "</tr>";
echo "</table>";

echo "<center>";
echo "<br><br><br><br><table border='1'>
<tr>
<th>Text</th>
<th>stars</th>
<th>Stars vs. Average</th>
<th>Friends Weight</th>
<th>User Vote Weight</th>
<th>Funny Weight</th>
<th>Useful Weight</th>
<th>Cool Weight</th>
</tr>";
while($row = $result->fetchArray())
  {
  if($row['date'] >= $start_date and $row['date'] <= $end_date and in_array($row['day'],$days)){
  echo "<tr>";
echo "<td>" . $row['content'] . "</td>";
echo "<td><center>" . $row['stars'] . "</td>";
echo "<td><center>" . number_format($row['adj_stars'],2) . "</td>";
echo "<td><center>" . number_format(max(log($row['user_friends'],2),0)/$friends_weight,2) . "</td>";
echo "<td><center>" . number_format($row['user_avg_votes']/$vote_weight,2) . "</td>";
echo "<td><center>" . number_format(($row['funny_votes']+1)/$funny_weight,2) . "</td>";
echo "<td><center>" . number_format(($row['useful_votes']+1)/$useful_weight,2) . "</td>";
echo "<td><center>" . number_format(($row['cool_votes']+1)/$cool_weight,2) . "</td>";
echo "</tr>";
  }
  }
 echo "</table>";
?>
</body>
</html>