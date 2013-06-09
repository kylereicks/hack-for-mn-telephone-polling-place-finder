<?php

if(!class_exists('Parse_Address')){
  class Parse_Address{

    private $input_address_string = '';

    private $geocoded_address;

    private $verification_request_xml = '';

    private $address_components = array();

    private $standardized_address_components = array();

    public function __construct($address_string){
      if(!empty($address_string)){
        include_once('settings.php');
        $this->input_address_string = $address_string;
        $this->geocoded_address = json_decode($this->geocode_address());
        $this->set_address_components();
        $this->verification_request_xml = $this->build_verification_xml();
        $this->set_standardized_address_components();
      }else{
        return false;
      }

    }

    public function get_standardized_address_components(){
      return $this->standardized_address_components;
    }

    private function geocode_address(){
      if(!strpos($this->input_address_string, ' mn ') && !strpos($this->input_address_string, 'minnesota')){
        $this->input_address_string .= ' minnesota';
      }

      return file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($this->input_address_string) . '&sensor=false');
    }

    private function reverse_geocode($lat, $lng){
      return file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . urlencode($lat) . ',' . urlencode($lng) . '&sensor=false');
    }

    private function standardize_address(){
//      return file_get_contents('https://production.shippingapis.com/ShippingAPITest.dll?API=Verify&XML=' . urlencode($this->verification_request_xml));
      return file_get_contents('http://production.shippingapis.com/ShippingAPITest.dll?API=Verify&XML=%3CAddressValidateRequest%20USERID=%22' . USPS_USERID . '%22%3E%3CAddress%20ID=%220%22%3E%3CAddress1%3E%3C/Address1%3E%3CAddress2%3E6406%20Ivy%20Lane%3C/Address2%3E%3CCity%3EGreenbelt%3C/City%3E%3CState%3EMD%3C/State%3E%3CZip5%3E%3C/Zip5%3E%3CZip4%3E%3C/Zip4%3E%3C/Address%3E%3C/AddressValidateRequest%3E');
    }

    private function set_standardized_address_components(){
      $standardized_address_components = array();
      $address_xml = new DOMDocument;
      $address_xml->loadXML($this->standardize_address());

      $address2 = $address_xml->getElementsByTagName('Address2');
      if(1 === $address2->length){
        preg_match('/^\d+/', $address2->item(0)->nodeValue, $street_number);
        $standardized_address_components['street_number'] = $street_number[0];
        $standardized_address_components['street_name'] = trim(preg_replace('/^' . $standardized_address_components['street_number'] . '/', '', $address2->item(0)->nodeValue, 1));
      }

      $city = $address_xml->getElementsByTagName('City');
      if(1 === $city->length){
        $standardized_address_components['city'] = $city->item(0)->nodeValue;
      }

      $state = $address_xml->getElementsByTagName('State');
      if(1 === $state->length){
        $standardized_address_components['state'] = $state->item(0)->nodeValue;
      }

      $zip = $address_xml->getElementsByTagName('Zip5');
      if(1 === $zip->length){
        $standardized_address_components['zip'] = $zip->item(0)->nodeValue;
      }

      $this->standardized_address_components = $standardized_address_components;
    }

    private function set_address_components(){
      $address_conponents = array();

      $address_components['status'] = $this->geocoded_address->status;
      $address_components['result_count'] = count($this->geocoded_address->results);

      if('OK' === $address_components['status'] && 1 == $address_components['result_count']){
        foreach($this->geocoded_address->results[0]->address_components as $address_component){
          if(in_array($address_component->types[0], array('street_address', 'route', 'street_number', 'locality', 'adminitrative_area_level_1', 'country', 'postal_code'))){
            $address_components[$address_component->types[0]] = $address_component->short_name;
          }
        }
        $address_components['formatted_address'] = $this->geocoded_address->results[0]->formatted_address;
        $address_components['lat'] = $this->geocoded_address->results[0]->geometry->location->lat;
        $address_components['lng'] = $this->geocoded_address->results[0]->geometry->location->lng;

        if(!isset($address_components['street_number'])){
          $reverse_geocoded_address = json_decode($this->reverse_geocode($address_components['lat'], $address_components['lng']));
          $address_components = array();
          
          $address_components['status'] = $reverse_geocoded_address->status;
          $address_components['result_count'] = 0 < count($reverse_geocoded_address->results) ? 1 : 0;

          foreach($reverse_geocoded_address->results[0]->address_components as $address_component){
            if(in_array($address_component->types[0], array('street_address', 'route', 'street_number', 'locality', 'administrative_area_level_1', 'postal_code'))){
              $address_components[$address_component->types[0]] = $address_component->short_name;
            }
          }
        }
      }

      $this->address_components = $address_components;
    }

    private function build_verification_xml(){
      $xml_output = '<?xml version="1.0"?>';
      $xml_output .= '<AddressValidateRequest USERID="' . USPS_USERID . '">';
        $xml_output .= '<Address ID="0">';
          $xml_output .= '<FirmName>';
          $xml_output .= '</FirmName>';
          $xml_output .= '<Address1>';
          $xml_output .= '</Address1>';
          $xml_output .= '<Address2>';
            $xml_output .= $this->address_components['street_number'] . ' ' . $this->address_components['route'];
          $xml_output .= '</Address2>';
          $xml_output .= '<City>';
            $xml_output .= $this->address_components['locality'];
          $xml_output .= '</City>';
          $xml_output .= '<State>';
            $xml_output .= $this->address_components['administrative_area_level_1'];
          $xml_output .= '</State>';
          $xml_output .= '<Zip5>';
            $xml_output .= $this->address_components['postal_code'];
          $xml_output .= '</Zip5>';
          $xml_output .= '<Zip4>';
          $xml_output .= '</Zip4>';
        $xml_output .= '</Address>';
      $xml_output .= '</AddressValidateRequest>';

      return $xml_output;
    }
  }
}
