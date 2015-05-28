<?php

class db {

    private $dbhost;
    private $dbuser;
    private $dbpass;
    private $dbname;
    private $conn;

    //En el constructor de la clase establecemos los par�metros de conexi�n con la base de datos

    function __construct($dbuser = 'root', $dbpass = '', $dbname = 'formjson', $dbhost = 'localhost') {

        $this->dbhost = $dbhost;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->dbname = $dbname;
    }

    //El m�todo abrir establece una conexi�n con la base de datos

    public function abrir() {
        $this->conn = mysqli_connect($this->dbhost, $this->dbuser, $this->dbpass, $this->dbname);
        if (mysqli_connect_errno()) {
            die('Error al conectar con mysql');
        }
    }

    //El m�todo "consulta" ejecuta la sentencia select que recibe por par�metro "$query" a la base de datos y devuelve un array asociativo con los datos que obtuvo de la base de datos para facilitar su posteiror manejo.

    public function consulta($query) {
        $valores = array();

        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            die('Error query BD:' . mysqli_error());
        } else {
            $num_rows = mysqli_num_rows($result);
            for ($i = 0; $i < $num_rows; $i++) {
                $row = mysqli_fetch_assoc($result);
                array_push($valores, $row);
            }
        }

        return $valores;
    }

    //La funci�n sql nos permite ejecutar una senetencia sql en la base de datos, se suele utilizar para senetencias insert y update.

    public function sql($sql) {
        $resultado = mysqli_query($this->conn, $sql);
        return $resultado;
    }

    //La funci�n id nos devuelve el identificador del �ltimo registro insertado en la base de datos

    public function id() {
        return mysqli_insert_id($this->conn);
    }

    //La funci�n "cerrar" finaliza la conexi�n con la base de datos.

    public function cerrar() {
        mysqli_close($this->conn);
    }

    //La funci�n 'escape' escapa los caracteres especiales de una cadena para usarla en una sentencia SQL

    public function escape($value) {
        return mysqli_real_escape_string($this->conn, $value);
    }

}
