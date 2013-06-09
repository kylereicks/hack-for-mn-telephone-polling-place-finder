<?php
if(!class_exists('Polling_Place_JSON_API')){
  class Polling_Place_JSON_API{

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

        $search = 'SELECT `Election Description`, `Polling Place Name`, `Polling Place Address`, `Polling Place City/State/Zip` FROM polling_place_data WHERE ' . $this->precinct_query();
        $polling_place_data = $db->query($search);
        $polling_place = $precinct_data->fetchAll(PDO::FETCH_ASSOC);

       echo json_encode($polling_place);

      }else{
        echo 'no zip';
      }

    }

    private function precinct_query(){
      $output = '';
      $output .= !empty($_GET['county']) ? '`County Code`=\'' . $_GET['county'] . '\'' : '';
      $output .= !empty($_GET['statemcd']) ? ' AND `MCD Code`=\'' . $_GET['statemcd'] . '\'' : '';
      $output .= !empty($_GET['ward']) ? ' AND `Ward Code`=\'' . $_GET['ward'] . '\'' : '';

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
