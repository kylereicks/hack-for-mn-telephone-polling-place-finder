<?php
if(!class_exists('Precinct_Finder_JSON_API')){
  class Precinct_Finder_JSON_API{

    public static function get_instance(){
      static $instance;

      if(null === $instance){
        $instance = new self();
      }

      return $instance;
    }

    private function __construct(){
      include_once('../../settings.php');
      header('Content-Type: application/json');
      if(!empty($_GET['zip'])){

        $db = $this->database_connect('mysql:dbname=' . DBNAME . ';host=' . HOST, USER, PASSWORD);

        $search = 'SELECT County, StateMCd, Ward FROM precinct_finder WHERE ' . $this->address_query();
        $precinct_data = $db->query($search);
        $precinct = $precinct_data->fetchAll(PDO::FETCH_ASSOC);

       echo json_encode($precinct);

      }else{
        echo 'no zip';
      }

    }

    private function address_query(){
      $evenOdd = $_GET['housenumber'] % 2 === 0 ? 'E' : 'O';
      $output = '';
      $output .= !empty($_GET['zip']) ? 'Zip=\'' . $_GET['zip'] . '\'' : '';
      $output .= !empty($_GET['city']) ? ' AND City=\'' . $_GET['city'] . '\'' : '';
      $output .= !empty($_GET['street']) ? ' AND StreetAddr=\'' . $_GET['street'] . '\'' : '';
      $output .= !empty($_GET['housenumber']) ? ' AND HouseNbrLo < \'' . $_GET['housenumber'] . '\' AND HouseNbrHi > \'' . $_GET['housenumber'] . '\'' : '';
      $output .= !empty($_GET['housenumber']) ? ' AND (OddEven=\'' . $evenOdd . '\' OR OddEven=\'B\')' : '';

      return $output;
    }

    private function database_connect($dsn, $user, $password){
      try{
        $db = new PDO($dsn, $user, $password);
      }catch(PDOexception $e){
        echo 'error';
        return false;
      }
      return $db;
    }

  }
}
