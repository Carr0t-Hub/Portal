<?php
Class dbObj{
        // Database Connection Start
        var $dbhost = "localhost";
        var $username = "root";
        var $password = "";
        var $dbname = "pds_db";
        var $conn;


        function getConnstring(){
            $con = mysqli_connect($this->dbhost, $this->username, $this->password, $this->dbname) or die("Connection failed: " . mysqli_connect_error());

            if (mysqli_connect_errno()){
                printf("Connection Failed: %s \n",mysqli_connect_error());
                exit();
            }else{
                $this->conn = $con;
            }
            return $this->conn; 
        }
}

?>