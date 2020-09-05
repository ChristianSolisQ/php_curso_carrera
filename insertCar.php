<?php
include('database_connection.php');
$form_data = json_decode(file_get_contents("php://input"));

$error = '';
$message = '';
$validation_error = '';
$codigo = '';
$nombre = '';
$titulo='';

if ($form_data->action == 'fetch_single_data') {
    $query = "SELECT * FROM carrera WHERE codigo='" . $form_data->codigo . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $output['codigo'] = $row['codigo'];
        $output['nombre'] = $row['nombre'];
        $output['titulo'] = $row['titulo'];
    }
}

elseif($form_data->action == 'Delete'){
    $query = "DELETE FROM carrera WHERE codigo='" . $form_data->codigo . "'";
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $output['message'] = 'Eliminada';
            }

}
else{
    if (empty($form_data->codigo)) {
        $error[] = 'Codigo is Required';
    } else {
        $codigo = $form_data->codigo;
    }
    if (empty($form_data->nombre)) {
        $error[] = 'Nombre is Required';
    } else {
        $nombre = $form_data->nombre;
    }
    if (empty($form_data->titulo)) {
        $error[] = 'Titulo is Required';
    } else {
        $titulo = $form_data->titulo;
    }
    if (empty($error)) {
        if ($form_data->action == 'Insertar') {
            $data = array(
                ':codigo'  => $codigo,
                ':nombre'  => $nombre,
                ':titulo'  => $titulo
            );
            $query = "
            INSERT INTO carrera 
                (codigo, nombre,titulo) VALUES 
                (:codigo, :nombre, :titulo)
            ";
            $statement = $connect->prepare($query);
            if ($statement->execute($data)) {
                $message = 'Carrera agregada';
            }
        }






        if($form_data->action =='Editar'){
            $data = array(
                ':codigo'  => $codigo,
                ':nombre'  => $nombre,
                ':titulo'         =>$titulo
            );
            $query = "
			UPDATE carrera SET nombre = :nombre, titulo = :titulo WHERE codigo= :codigo";
            $statement = $connect->prepare($query);
            if ($statement->execute($data)) {
                $output['message'] = 'Data Edited';
            }
        }
        }
        else {
                $validation_error = implode(", ", $error);
            }
            $output = array(
                'error'  => $validation_error,
                'message'  => $message
            );
        }
   
echo json_encode($output);

?>