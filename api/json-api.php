<?php
if(!class_exists('JSON_API')){
  class JSON_API{

    public static function get_instance(){
      static $instance;

      if(null === $instance){
        $instance = new self();
      }

      return $instance;
    }

    private function __construct(){
      header('Content-Type: application/json');
      if(!empty($_GET['zip'])){

        $db = $this->database_connect('mysql:dbname', 'user', 'password');

        $search = 'SELECT * FROM precinct_finder WHERE ' . $this->address_query();
        $precinct_data = $db->query($search);
        $precinct = $precinct_data->fetchAll(PDO::FETCH_ASSOC);

//        print_r($precinct);
        
       echo json_encode($precinct);

      }else{
        echo 'no zip';
      }

    }

    private function address_query(){
      $output = '';
      $output .= !empty($_GET['zip']) ? 'Zip=\'' . $_GET['zip'] . '\'' : '';
      $output .= !empty($_GET['city']) ? ' AND City=\'' . $_GET['city'] . '\'' : '';
      $output .= !empty($_GET['street']) ? ' AND StreetAddr=\'' . $_GET['street'] . '\'' : '';

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
