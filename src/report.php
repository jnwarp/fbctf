<?php

# WARNING: THIS SCRIPT IS PUBLIC WITHOUT A PASSWORD
#          Do not modify to return personal information
#
# This script will output the top high scores

# save the file as a CSV
header('Content-Type: text/csv');

# get database settings
$settings = parse_ini_file('../settings.ini');

# connect to database
$db = new mysqli(
	$settings['DB_HOST'] . ':' . $settings['DB_PORT'],
	$settings['DB_USERNAME'],
	$settings['DB_PASSWORD'],
	$settings['DB_NAME']
);

# query to return high scores
$query = <<<EOT
SELECT t.`points`, r.`token` 
FROM teams AS t 
JOIN teams_data AS d 
    ON (t.`id` = d.`team_id`) 
JOIN registration_tokens AS r 
    ON (t.`id` = r.`team_id`) 
ORDER BY t.`points` ASC, r.`token` ASC;
EOT;
$result = $db->query($query);

# print out data in csv format
echo "points,token\n";
while($row = $result->fetch_assoc()) {
	$out = '';
	foreach ($row as $item) {
		# escape wierd characters
		$item = htmlspecialchars($item);
	
		$out = $out . $item . ',';
	}

	# do not print out last comma
	echo substr($out, 0, -1) . "\n";
}

