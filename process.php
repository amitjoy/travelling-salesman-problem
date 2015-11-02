<?php
ini_set("error_reporting", E_ALL & ~E_NOTICE);
ini_set("max_execution_time",12000);

$no = (int)$_GET['no_of_cities'];
for($i = 1; $i <= $no ; $i++)
{
	$arr[$i] = "City ".$i;
}
if(!isset($no))
header("Location:index.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href='amit_button.css'/>
<title>TSP : PHP Implementation</title>
<style>
body {
	text-align: center;
	margin: 0px; padding: 0px;
}
body, td, th, input {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
}
h1 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 15px;
	text-align: center;
}
.cityCell {
	width: 60px;
}
.input {
	background-color: #CCFFFF;
	border: 1px solid #ccc;
	padding: 1px;
	margin: 1px;
}
#container {
	margin: 0 auto 0 auto; padding: 10px;
	width: auto;
	text-align: left;
	border-left: 0px solid #333;
	border-right: 0px solid #333;
	border-bottom: 0px solid #333;
}
form {
	margin: 0px; padding: 0px;
}
.debug td {
	padding: 0 2px 0 2px;
}
</style>

</head>

<body>
<div id="container">
<h1><a href="index.php">TSP : No of cities : <?=$no;?></a></h1>
<form method="post">
<table width="550" border="0" cellspacing="6" cellpadding="2" style='border: 1px solid #999;' align="center">
  <tr>
    <td ><strong>Cities</strong></td>
	<?php
	foreach($arr as $k=>$v)
	{
		echo "<td align='center' class='cityCell'>".$v."</td>";
	}
	?>
  </tr>
  <?php
	foreach($arr as $k=>$v)
	{
	echo "
	<tr>
    <td width='20%'>".$v."</td>";
	for($i = 1; $i <=count($arr); $i++) {
	if($k>$i)
	echo "<td><div align='center'><div></td>";
	elseif($k == $i) {
	echo"
    <td bgcolor='#CC3333'><div align='center'>0</div></td>";
	}
	elseif(!$_POST[$k."_".$i])
	echo "
    <td><div align='center'>
      <input name='".$k."_".$i."' type='text' autocomplete='off' class='input' id='textfield' size='4' maxlength='4' value='".rand(1,67)."'/> 
    </div></td>
  ";
  else
 	echo "
    <td><div align='center'>
      <input name='".$k."_".$i."' type='text' autocomplete='off' class='input' id='textfield' size='4' maxlength='4' value='".$_POST[$k."_".$i]."'/> 
    </div></td>
  ";
		}
		echo "</tr>";
	}
  ?>
 
</table>
<br />
<br />
<table border="0" cellspacing="2" cellpadding="0" style='border: 1px solid #999;' align="center">
  <tr>
    <td>Population</td>
    <td align="right"><input name="population" type="text" class="input" autocomplete="off" id="population" value="<?=$_POST['population']?>" size="5" maxlength="5" /></td>
  </tr>
  <tr>
    <td>Generations</td>
    <td align="right"><input name="generations" type="text" class="input" autocomplete="off" id="textfield24" value="<?=$_POST['generations']?>" size="5" maxlength="5" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input class='amit_button' type="submit" name="button" id="button" value="Calculate Shortest Route" /></td>
  </tr>
</table>

</form>
<?php
if (!empty($_POST)) {
	define('CITY_COUNT', count($arr));
	$population = $_POST['population'] + 0;
	if ($population > 999)
		$population = 999;
		
	$generations = $_POST['generations'] + 0;
	$elitism = $_POST['elitism'] + 0;
	$names = array();
	$distances = array();
	
	$initialPopulation = array();
	$currentPopulation = array();
	
	# Take user city names and put it into an array
	for ($i = 1; $i <= CITY_COUNT; $i++) {
		$names[$i] = $_POST['name'.$i];
	}
	
	# Take user distance data and put it into a multidimensional array
	for ($i = 1; $i <= CITY_COUNT; $i++) {
		for ($j = 1; $j <= CITY_COUNT; $j++) {
			if (isset($_POST[$i . '_' . $j]))
				$distances[$i][$j] = $_POST[$i . '_' . $j];
			else if (isset($_POST[$j . '_' . $i]))
				$distances[$i][$j] = $_POST[$j . '_' . $i];
			else
				$distances[$i][$j] = 32767;
		}
	}
	
	# Building our initial population
	for($i = 0; $i < $population; $i++) {
		$initialPopulation[$i] = pickRandom();
	}
	
	for ($k = 1; $k <= $generations; $k++) {
		echo "<div><strong>Generation '".$k."'</strong></div>\n";
		# Rating population in accordance with fitness value.
		echo "<pre>";
		$i = 0;
		$distanceSum = 0;
		$biggest = 0;
		foreach ($initialPopulation AS $entity) {
			$currentPopulation[$i]['dna'] = $entity;
			$currentPopulation[$i]['rate'] = rate($entity, $distances);
			$distanceSum += $currentPopulation[$i]['rate'];
			if ($currentPopulation[$i]['rate'] > $biggest)
				$biggest = $currentPopulation[$i]['rate'];
			$i++;
		}
		$biggest += 1;
		$chancesSum = 0;
		for ($i = 0; $i < $population; $i++ ) {
			$currentPopulation[$i]['metric'] = $biggest - $currentPopulation[$i]['rate'];
			$chancesSum += $currentPopulation[$i]['metric'];
		}
		for ($i = 0; $i < $population; $i++ ) {
			$currentPopulation[$i]['chances'] = $currentPopulation[$i]['metric'] / $chancesSum;
		}
		util::sort($currentPopulation, 'rate');
		$ceilSum = 0;
		for ($i = 0; $i < $population; $i++ ) {
			$currentPopulation[$i]['floor'] = $ceilSum;
			$ceilSum += $currentPopulation[$i]['chances'];
		}
		debug($currentPopulation);
		echo "</pre>\n";
		if (converging($initialPopulation))
			break;
			
		#Breeding time
		$initialPopulation = array();
		for ($j = 0; $j < $elitism; $j++) {
			$initialPopulation[] = $currentPopulation[$j]['dna'];
		}
		for ($j = 0; $j < $population - $elitism; $j++) {
			$rouletteMale = rand(0, 100) / 100;
			
			for ($i = $population - 1; $i >= 0; $i--) {
				if ($currentPopulation[$i]['floor'] < $rouletteMale) {
					$dad = $currentPopulation[$i]['dna'];
					break;
				}
			}
			
			$rouletteFemale = rand(0, 100) / 100;
			
			for ($i = $population - 1; $i >= 0; $i--) {
				if ($currentPopulation[$i]['floor'] < $rouletteFemale) {
					$mom = $currentPopulation[$i]['dna'];
					break;
				}
			}
			
			//$child = mate($mom, $dad);
			//$initialPopulation[] = $child;
		}
		
	}

	echo "<div>The best solution : <strong>{$currentPopulation[0]['dna']}</strong> with a path cost of <strong>".rate($currentPopulation[0]['dna'], $distances)."</strong> which took <strong>'".($k-1)."'</strong> generations.</div>\n";
}
?>
</div>
</body>
</html>
<?php
function converging($pop) {
	$items = count(array_unique($pop));
	if ($items == 1)
		return true;
	else
		return false;
}
function pickRandom() {
	$choices = range(1,CITY_COUNT);
	shuffle($choices);
	return implode('-',$choices);
}

function rate($dna, $distances) {
	$mileage = 0;
	$letters = explode("-",$dna);;
	for ($i = 0; $i < CITY_COUNT - 1; $i++) {
		$mileage += $distances[let2num($letters[$i])][let2num($letters[$i+1])];
	}
	return $mileage;
}

function debug($ar) {
	echo "<table class='debug' width='50%'>";
	echo "<tr><th>ID</th><th>MAPPING</th><th>Cost</th><th>Fitness</th></tr>\n";
	foreach($ar as $element => $value) {
		//echo "<tr><td>" . leadingZero($element) . "</td><td>" . $value['dna'] . "</td><td>" . $value['rate'] . "</td><td>" . sprintf("%01.2f", $value['chances'] * 100) . "%</td></tr>\n";
		echo "<tr><td>" . leadingZero($element) . "</td><td>" . $value['dna'] . "</td><td>" . $value['rate'] . "</td><td>". sprintf("%01.2f", $value['chances'] * 100) . "%</td></tr>\n";
	
	}
	echo "</table>\n";
}

function leadingZero($value) {
	if ($value < 10)
		$value = '00' . $value;
	else if ($value < 100)
		$value = '0' . $value;
	return $value;
}

function mate($mommy, $daddy) {  #Combines productions randomly from both and if results are repeated we do it again.
	$arr = range(1,CITY_COUNT);
	$baby = array();
	for($i = 1; $i<=CITY_COUNT-1;$i++){
	array_push($baby,1);
	array_push($baby,"-");
	}
	array_pop($baby);

	foreach($arr as $v)
	{	
		while(in_array($v,$baby)){
		$baby = array();
		for($i = 0; $i < CITY_COUNT; $i++)
		{
			$chosen = mt_rand(0,1);
			$mom = explode("-",$mommy);
			$dad = explode("-",$daddy);
			if($chosen) {
				array_push($baby,$mom[0]);
				array_push($baby,"-");
				}
			else {
				array_push($baby,$dad[0]);
				array_push($baby,"-");
				}
		}
		}
	}
	array_pop($baby);
	foreach($baby as $v)
	{
		$baby1.=$v;
	}
	return $baby1;
}

function let2num($char) {
	return $char;
}

class util {
    static private $sortfield = null;
    static private $sortorder = 1;
    static private function sort_callback(&$a, &$b) {
        if($a[self::$sortfield] == $b[self::$sortfield]) return 0;
        return ($a[self::$sortfield] < $b[self::$sortfield])? -self::$sortorder : self::$sortorder;
    }
    static function sort(&$v, $field, $asc=true) {
        self::$sortfield = $field;
        self::$sortorder = $asc? 1 : -1;
        usort($v, array('util', 'sort_callback'));
    }
}