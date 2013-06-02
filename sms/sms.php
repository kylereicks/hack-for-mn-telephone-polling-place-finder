<?php
$text = $_REQUEST['Body'];

//$text = '9545 76th st nw morristown, mn 55052';

preg_match('/5[56][0-9]{3}/', $text, $zip);
preg_match('/^\d+/', $text, $house_number);
preg_match('/(\w+),?\sMN\s\d/i', $text, $city);
preg_match('/' . $house_number[0] . '(.+)' . $city[1] . '/i', $text, $street);

$api_call = 'http://hackformn.kylereicks.com/?zip=' . $zip[0] . '&city=' . urlencode($city[1]) . '&street=' . urlencode(trim($street[1])) . '&housenumber=' . $house_number[0];

$json = file_get_contents($api_call);

$data = json_decode($json);

$precinct_name = $data[0]->McdName;

$precinct_name = is_string($precinct_name) && 140 > strlen($precinct_name) ? $precinct_name : 'error';

header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<Response>
    <Sms>Your precinct is <?php echo $precinct_name ?></Sms>
</Response>
