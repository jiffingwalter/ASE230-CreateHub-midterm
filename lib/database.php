<?php
// database object, creates pdo connection to database and provides options for handling the connection
class Database{
    public $pdo;
    private $host;
    private $name;
    private $user;
    private $pass;
    private $db_options = [];

    // initalize database on object creation
    function __construct()
    {
        $this->host='localhost';
        $this->name='createhub';
        $this->user='root';
        $this->pass='';

        $this->db_options = [ // sets database options
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            ];
        $this->createPDO();
    }

    // create PDO connection function
    function createPDO(){
        $this->pdo = new PDO('mysql:host='.$this->host.';dbname='.$this->name.';charset=utf8mb4',$this->user,$this->pass,$this->db_options);
    }

    // runs a query and returns true if it could get it and false if not
    function testConnection(){
        $query=$this->pdo->query('SELECT uid FROM users');
        return ($query->rowCount()>0);
    }
}