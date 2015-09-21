<?php

// gets the url from the previous page
$to_crawl = $_REQUEST["url"];

// initializes title array
$titleArray = array(); 

// initializes skills string
$skills = "";
foreach($to_crawl as $this_crawl){
	// extracts the skills and title on page
	$input = @file_get_contents($this_crawl);
	$regex_skills = "~\/jobs\/tag\/.*>(.*)<\/a>~";
	$regex_title = "~<title>(.*)</title>~";
	
	preg_match_all($regex_skills, $input, $matches_skills);
	preg_match_all($regex_title, $input, $matches_title);

	// sets title variable and adds it to titleArray
	$t = $matches_title[1][0];
	array_push($titleArray, $t);
	
	// creates sting from array of skills	
	$sImplode = implode(" ", $matches_skills[0]);

	// adds this string to the string of skills
	$skills = $skills . $sImplode;
}

// puts string back into array
$sExplodePlural = explode(" ", $skills);	

$allSkills = array();
// weeds out the extra html so we are just left with the skill
foreach($sExplodePlural as $sExplodeSingle){
	if(preg_match_all("~>(.*)~", $sExplodeSingle, $currentSkill))
		array_push($allSkills, $currentSkill[1][0]);
}

// gets rid of duplicate skills and increments the value of the existing skill 
$finalSkills = array_count_values($allSkills);

// sorts the array by descending value
arsort($finalSkills);

?>

<html>
<head>
	<title>Skills gathered from: <?php if(count($titleArray) > 1){ echo "Multiple Sources"; }else{ echo $titleArray[0]; } ?></title>
	<link rel="stylesheet" type="text/css" href="theme-index.css">
	<link href='https://fonts.googleapis.com/css?family=Josefin+Slab' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
</head>

<body>
	<div id="main">
	<h2 style="font-family: 'Josefin Slab', serif">Top skills employers are looking for on:</h2>
	<br> 
	<?php foreach($titleArray as $t){ 
		echo '<span class="title">' . $t . "</span>" . "<br>"; 
	} 
	?>
	<table style="font-family:'Lato', sans-serif" >
		<tr>
			<td class="value">
			</td>
			<td class="skill">
				Skill
			</td>
			<td class="value">
				Number of Occurances
			</td>
		</tr>
		<?php 
		$i=1;
		foreach($finalSkills as $key => $value){ ?>
		<tr class="<?php if($i<4){ echo "topSkills"; } elseif($i<7){ echo "medSkills"; } else{ echo "bottomSkills"; } ?>">
			<td class="count">
				<?php echo $i."." ?>
			</td>
			<td class="skill">
				<?php echo $key; ?>
			</td>
			<td class="value">
				<?php echo $value; ?>
			</td>
		</tr>
		<?php 
			$i = $i+1;
		} ?>
	</table>
	</div>
</body>
</html>