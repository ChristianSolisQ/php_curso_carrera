<?php
include('database_connection.php');
$form_data = json_decode(file_get_contents("php://input"));

$error = '';
$message = '';
$validation_error = '';
$codigo = '';
$nombre = '';
$creditos='';
$horas_semanales='';
$nivel='';
$ciclo='';
$codigo_carrera='';
if ($form_data->action == 'fetch_single_data') {
    $query = "SELECT * FROM curso WHERE codigo='" . $form_data->codigo . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    foreach ($result as $row) {
        $output['codigo'] = $row['codigo'];
        $output['nombre'] = $row['nombre'];
        $output['creditos'] = $row['creditos'];
        $output['horas_semanales'] = $row['horas_semanales'];
        $output['nivel'] = $row['nivel'];
        $output['ciclo'] = $row['ciclo'];
        $output['codigo_carrera'] = $row['codigo_carrera'];
    }
}
elseif($form_data->action == 'Delete'){
    $query = "DELETE FROM curso WHERE codigo='" . $form_data->codigo . "'";
            $statement = $connect->prepare($query);
            if ($statement->execute()) {
                $output['message'] = 'Eliminado';
            }

}
else{

//insert and edit data
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
if (empty($form_data->creditos)) {
    $error[] = 'Creditos is Required';
} else {
    $creditos = $form_data->creditos;
}
if (empty($form_data->horas_semanales)) {
    $error[] = 'Horas semanales is Required';
} else {
    $horas_semanales = $form_data->horas_semanales;
}
if (empty($form_data->nivel)) {
    $error[] = 'Nivel is Required';
} else {
    $nivel = $form_data->nivel;
}
if (empty($form_data->ciclo)) {
    $error[] = 'Ciclo is Required';
} else {
    $ciclo = $form_data->ciclo;
}
if (empty($form_data->codigo_carrera)) {
    $error[] = 'Codigo de la carrera is Required';
} else {
    $codigo_carrera = $form_data->codigo_carrera;
}
if (empty($error)) {
    if ($form_data->action == 'Insertar') {
        $data = array(
            ':codigo'  => $codigo,
            ':nombre'  => $nombre,
            ':creditos'  => $creditos,
            ':horas_semanales'  => $horas_semanales,
            ':nivel'  => $nivel,
            ':ciclo'  => $ciclo,
            ':codigo_carrera'  => $codigo_carrera
        );
        $query = "
        INSERT INTO curso 
            (codigo, nombre,creditos,horas_semanales,nivel,ciclo,codigo_carrera) VALUES 
            (:codigo, :nombre, :creditos, :horas_semanales, :nivel, :ciclo, :codigo_carrera)
        ";
        $statement = $connect->prepare($query);
        if ($statement->execute($data)) {
            $message = 'Curso agregado';
        }
    }
    if($form_data->action =='Editar'){
        $data = array(
            ':codigo'  => $codigo,
            ':nombre'  => $nombre,
            ':creditos'  => $creditos,
            ':horas_semanales'  => $horas_semanales,
            ':nivel'  => $nivel,
            ':ciclo'  => $ciclo,
            ':codigo_carrera'  => $codigo_carrera
        );
        $query = "
        UPDATE curso SET nombre = :nombre, creditos = :creditos, horas_semanales = :horas_semanales, nivel = :nivel, ciclo = :ciclo, codigo_carrera = :codigo_carrera WHERE codigo= :codigo";
        $statement = $connect->prepare($query);
        if ($statement->execute($data)) {
            $output['message'] = 'Data Edited';
        }
    }
}  else {
        $validation_error = implode(", ", $error);
    }
    $output = array(
        'error'  => $validation_error,
        'message'  => $message
    );
}



echo json_encode($output);


?>