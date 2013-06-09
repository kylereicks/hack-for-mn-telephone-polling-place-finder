<?php
include('../parse-address.php');
$text = $_REQUEST['Body'];

$address = new Parse_Address($text);
extract($address->get_standardized_address_components());

$api_call = 'http://hackformn.kylereicks.com/?zip=' . $zip . '&city=' . urlencode($city) . '&street=' . urlencode($street_name) . '&housenumber=' . $street_number;

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
