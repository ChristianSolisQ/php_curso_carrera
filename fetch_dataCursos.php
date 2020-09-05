<?php
//fetch_dataCursos.php
include('database_connection.php');

$query= "SELECT * FROM curso";

$statement = $connect->prepare($query);
if($statement->execute()){
    //PDO::FETCH_ASSOC Obtiene la siguiente fila de un conjunto de resultados
    while($row =$statement->fetch(PDO::FETCH_ASSOC)){
     $data[]=$row;
    }
    echo json_encode($data);
}
?>