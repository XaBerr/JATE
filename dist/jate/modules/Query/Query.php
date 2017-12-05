<?php
  jRequire("../JException/JException.php");
  jRequire("../JConfig/JConfig.php");
  jRequire("../Connection/Connection.php");
  trait Query {
    public $connection;
    public $currentConnection;
    public function __construct() {
      $this->connection = [];
      $this->currentConnection = null;
    }
    public function addConnection( $_path, $_name = "default" ) {
      if(!is_string($_path))
        throw new JException("Parameter must be a string.", 1);
      try {
        $jConfig = new JConfig($_path);
        if($jConfig->enable) {
          $connection = new Connection($jConfig);
          $this->connection["$_name"] = $connection;
          $this->currentConnection = $connection;
          foreach ($this->modules as &$module)
            if(isset($module->currentConnection))
              $module->addConnection($_path, $_name);
        }
      } catch (Exception $e) {
        throw new JException($e->getMessage(), 1);
      }
    }
    public function setConnection( $_name = "default" ) {
      if(!is_string($_name))
        throw new JException("Parameter must be a string.", 1);
      if(!isset($this->connection["$_name"]))
        throw new JException("This connection name does not exist.", 1);
      $this->currentConnection = $this->connection["$_name"];
    }
    public function query( $_query ) {
      if(!is_string($_query))
        throw new JException("Parameter must be a string.", 1);
      try {
        $temp = $this->currentConnection->database->query($_query);
      } catch (Exception $e) {
        throw new JException($e->getMessage(), 1);
      }
      return $temp;
    }
    public function queryInsert( $_query ) {
      if(!is_string($_query))
        throw new JException("Parameter must be a string.", 1);
      try {
        $temp = $this->currentConnection->database->queryInsert($_query);
      } catch (Exception $e) {
        throw new JException($e->getMessage(), 1);
      }
      return $temp;
    }
    public function queryFetch( $_query ) {
      if(!is_string($_query))
        throw new JException("Parameter must be a string.", 1);
      try {
        $temp = $this->currentConnection->database->queryFetch($_query);
      } catch (Exception $e) {
        throw new JException($e->getMessage(), 1);
      }
      return $temp;
    }
    public function queryArray( $_query ) {
      if(!is_string($_query))
        throw new JException("Parameter must be a string.", 1);
      try {
        $temp = $this->currentConnection->database->queryArray($_query);
      } catch (Exception $e) {
        throw new JException($e->getMessage(), 1);
      }
      return $temp;
    }
  }
?>
