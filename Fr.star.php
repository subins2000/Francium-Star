<?php
namespace Fr;

/**
.---------------------------------------------------------------------------.
| The Francium Project                                                      |
| ------------------------------------------------------------------------- |
| This software 'Francium Star' is a part of the Francium (Fr) project.     |
| http://subinsb.com/the-francium-project                                   |
| ------------------------------------------------------------------------- |
|     Author: Subin Siby                                                    |
| Copyright (c) 2014 - 2015, Subin Siby. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the Apache License, Version 2.0              |
|            http://www.apache.org/licenses/LICENSE-2.0                     |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/

/**
.--------------------------------------------------------------------------.
|  Software: Francium Star                                                 |
|  Version: 0.1  (2015-06-16)                                              |
|  Contact: http://github.com/subins2000/Francium-Star                     |
|  Documentation: https://subinsb.com/Francium-Star                        |
|  Support: https://github.com/subins2000/Francium-Star/issues             |
'--------------------------------------------------------------------------'
*/

ini_set("display_errors", "on");

class Star {

  /**
   * ------------
   * BEGIN CONFIG
   * ------------
   * Edit the configuraion
   */
  
  public $default_config = array(
    /**
     * Information about who uses logSys
     */
    "info" => array(
      "company" => "My Site",
      "email" => "mail@subinsb.com"
    ),
    
    /**
     * Database Configuration
     */
    "db" => array(
      "host" => "",
      "port" => 3306,
      "username" => "",
      "password" => "",
      "name" => "",
      "table" => "Fr_star"
    )
  );
  
  /* ------------
   * END Config.
   * ------------
   * No more editing after this line.
   */
  
  public $config = array();
  
  /**
   * Log something in the Francium.log file.
   * To enable logging, make a file called "Francium.log" in the directory
   * where "class.logsys.php" file is situated
   */
  public static function log($msg = ""){
    $log_file = __DIR__ . "/Francium.log";
    if(file_exists($log_file)){
      if($msg != ""){
        $message = "[" . date("Y-m-d H:i:s") . "] $msg";
        $fh = fopen($log_file, 'a');
        fwrite($fh, $message . "\n");
        fclose($fh);
      }
    }
  }
  
  public $id = null;
  
  public function __construct($config, $id = null){
    $this->config = array_merge($this->default_config, $config);
    
    try {
      $this->dbh = new \PDO("mysql:dbname=". $this->config['db']['name'] .";host=". $this->config['db']['host'] .";port=". $this->config['db']['port'], $this->config['db']['username'], $this->config['db']['password']);
      
      $this->id = $id;
      
    }catch(\PDOException $e) {
      /**
       * Couldn't connect to Database
       */
      self::log('Couldn\'t connect to database. Check \Fr\LS::$config["db"] credentials');
      return false;
    }
  }
  
  /**
   * Set a rate
   */
  public function addRating($user_id, $rating){
    if($rating <= 5.0){
      $sql = $this->dbh->prepare("SELECT COUNT(1) FROM `{$this->config['db']['table']}` WHERE `user_id` = ? AND `rate_id` = ?");
      $sql->execute(array($user_id, $this->id));
        
      if($sql->fetchColumn() == "0"){
        $sql = $this->dbh->prepare("INSERT INTO `{$this->config['db']['table']}` (`rate_id`, `user_id`, `rate`) VALUES(?, ?, ?)");
        return $sql->execute(array($this->id, $user_id, $rating));
      }else{
        $sql = $this->dbh->prepare("UPDATE `{$this->config['db']['table']}` SET `rate` = ? WHERE `user_id` = ? AND `rate_id` = ?");
        return $sql->execute(array($rating, $user_id, $this->id));
      }
    }
  }
  
  public function getRating($html_class = "", $type = "html"){
    $sql = $this->dbh->prepare("SELECT * FROM `{$this->config['db']['table']}` WHERE `rate_id` = ?");
    $sql->execute(array($this->id));
    $results = $sql->fetchAll(\PDO::FETCH_ASSOC);
    
    if(count($results) == 0){
      $rate_times = 0;
      $rate_value = 0;
      $rate_bg = 0;
    }else{
      foreach($results as $result){
        $rate_db[] = $result;
        $sum_rates[] = $result['rate'];
      }
      $rate_times = count($rate_db);
      $sum_rates = array_sum($sum_rates);
      $rate_value = $sum_rates/$rate_times;
      $rate_bg = (($rate_value)/5)*100;
    }
    
    if($type == "html"){
      $html = '<div class="Fr-star '. $html_class .'" data-title="'. round($rate_value, 2) .' / 5 by '. $rate_times .' ratings" data-rating="'. $rate_value .'">';
        $html .= '<div class="Fr-star-value" style="width: '. $rate_bg .'%"></div>';
        $html .= '<div class="Fr-star-bg"></div>';
      $html .= '</div>';
      
      return $html;
    }else if($type == "rate_value"){
      return $rate_value;
    }else if($type == "rate_percentage"){
      return $rate_bg;
    }else if($type == "rate_times"){
      return $rate_times;
    }
  }
  
  public function userRating($user_id){
    $sql = $this->dbh->prepare("SELECT `rate` FROM `{$this->config['db']['table']}` WHERE `user_id` = ?");
    $sql->execute(array($user_id));
    return $sql->fetchColumn();
  }
}
