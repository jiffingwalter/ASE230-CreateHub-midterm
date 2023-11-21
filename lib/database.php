<?php 
// database object, creates pdo connection to database and provides options for handling the connection
class Database{
    private $pdo;
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

    // performs query operation with pdo object and returns SINGLE result in array
    function query($query){
        $newQuery = $this->pdo->query($query);
        return $newQuery->fetch();
    }

    // performs prepared query operation with pdo object and returns SINGLE result in array
    // $query is for the prepared query, $parameters is the stuff to input
    function preparedQuery($query,$parameters){
        $newQuery = $this->pdo->prepare($query);
        $newQuery->execute($parameters);
        return $newQuery->fetch();
    }

    // performs query operation with pdo object and returns result in array, use for queries with multiple results
    function queryAll($query){
        $newQuery = $this->pdo->query($query);
        return $newQuery->fetchAll();
    }

    // performs prepared query operation with pdo object and returns result, use for queries with multiple results
    // $query is for the prepared query, $parameters is the stuff to input
    function preparedQueryAll($query,$parameters){
        $newQuery = $this->pdo->prepare($query);
        $newQuery->execute($parameters);
        return $newQuery->fetchAll();
    }

    // checks if any result was found in a processed query, returns true or false (just saves having to type row count and etc)
    function resultFound($query){
        return !($query==false || (count($query)<1));
    }

    // converts date format from sql to perferred format
    function convertDate(){

    }
}