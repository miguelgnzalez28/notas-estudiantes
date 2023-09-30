<?php
session_start();
$alumnos_registrados = [];
$alumno_actual = [];

if (isset($_SESSION['dataEstudiante'])) {
    $alumnos_registrados = $_SESSION['dataEstudiante'];
}

if (isset($_POST['eliminar'])) {
    session_destroy();
    header("Location: index.php");
}

class Estudiante
{
    public $cedula;
    public $nombre;
    public $nota_matematicas;
    public $nota_fisica;
    public $nota_programacion;
    public $aprobado_matematicas;
    public $aprobado_fisica;
    public $aprobado_programacion;

    public function __construct($cedula, $nombre, $nota_matematicas, $nota_fisica, $nota_programacion)
    {
        $this->cedula = $cedula;
        $this->nombre = $nombre;
        $this->nota_matematicas = $nota_matematicas;
        $this->nota_fisica = $nota_fisica;
        $this->nota_programacion = $nota_programacion;
    }

    public function getCedula()
    {
        return $this->cedula;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getNotaMatematicas()
    {
        return $this->nota_matematicas;
    }

    public function getNotaFisica()
    {
        return $this->nota_fisica;
    }

    public function getNotaProgramacion()
    {
        return $this->nota_programacion;
    }

    public function aprobarMatematicas()
    {
        $this->aprobado_matematicas = true;
    }

    public function aprobarFisica()
    {
        $this->aprobado_fisica = true;
    }

    public function aprobarProgramacion()
    {
        $this->aprobado_programacion = true;
    }
}

if ((isset($_POST['cedula']) && isset($_POST['nombre']) && isset($_POST['nota_matematicas'])) && (isset($_POST['nota_fisica']) && isset($_POST['nota_programacion']))) {
    if (!empty($_POST['cedula']) && !empty($_POST['nombre']) && !empty($_POST['nota_matematicas']) && !empty($_POST['nota_fisica']) && !empty($_POST['nota_programacion'])) {
        $cedula = $_POST['cedula'];
        $nombre = $_POST['nombre'];
        $nota_matematicas = $_POST['nota_matematicas'];
        $nota_fisica = $_POST['nota_fisica'];
        $nota_programacion = $_POST['nota_programacion'];

        $alumno_actual = new Estudiante($cedula, $nombre, $nota_matematicas, $nota_fisica, $nota_programacion);
        array_push($alumnos_registrados, $alumno_actual);
        $_SESSION['dataEstudiante'] = $alumnos_registrados;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Act. 2</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
          crossorigin="anonymous">
</head>

<body>
<br><h1>Notas de Estudiantes</h1><br><br>
<form action="index.php" method="post" class="form-inline">
    <h2>Ingresa los siguientes datos</h2>
    <div class="form-group mb-2 ">
        <label for="cedula">Cédula del estudiante</label>
        <input pattern="[0-9]+" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" class="form-control" type="number" id="cedula" name="cedula">

        <label for="nombre">Nombre y Apellido</label>
        <input onkeyup="lettersOnly(this)" class="form-control" type="text" id="nombre" name="nombre">

        <label for="nota_matematicas">Nota de matemáticas</label>
        <input type="number" step=".01" class="form-control" id="nota_matematicas" name="nota_matematicas">

        <label for="nota_fisica">Nota de física</label>
        <input type="number" step=".01" class="form-control" id="nota_fisica" name="nota_fisica">

        <label for="nota_programacion">Nota de programación</label>
        <input type="number" step=".01" class="form-control" id="nota_programacion" name="nota_programacion">

        <input type="submit" value="Agregar" name="btn" class="btn btn-success">
        <input type="submit" value="Reiniciar" name="eliminar" class="btn btn-danger">
        <a href="../index.php" class="btn btn-secondary">Regresar</a>
    </div>
</form>

<h2>Lista de Alumnos</h2>

<table class="table">
    <thead>
    <tr>
        <th scope="col">Cedula</th>
        <th scope="col">Nombre</th>
        <th scope="col">Nota de Matematica</th>
        <th scope="col">Nota de Fisica</th>
        <th scope="col">Nota de Programacion</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($alumnos_registrados as $estudiante) {
        echo "<tr>";
        echo "<td>", $estudiante->getCedula(), "</td>";
        echo "<td>", $estudiante->getNombre(), "</td>";
        echo "<td>", $estudiante->getNotaFisica(), "</td>";
        echo "<td>", $estudiante->getNotaMatematicas(), "</td>";
        echo "<td>", $estudiante->getNotaProgramacion(), "</td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
<br><br>

<div>
    <h4>Datos Filtrados:</h4>
    <div class="tabla">
        <table class="table">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Nota Promedio</th>
                <th scope="col">Estudiantes Aprobados</th>
                <th scope="col">Estudiantes Aplazados</th>
                <th scope="col">Nota Máxima</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $suma_notas_matematicas = 0;
            $suma_notas_fisica = 0;
            $suma_notas_programacion = 0;
            $cantidad_aprobados_matematicas = 0;
            $cantidad_aprobados_fisica = 0;
            $cantidad_aprobados_programacion = 0;
            $cantidad_aplazados_matematicas = 0;
            $cantidad_aplazados_fisica = 0;
            $cantidad_aplazados_programacion = 0;
            $nota_maxima_matematicas = 0;
            $nota_maxima_fisica = 0;
            $nota_maxima_programacion = 0;

            foreach ($alumnos_registrados as $estudiante) {
                $suma_notas_matematicas += $estudiante->getNotaMatematicas();
                $suma_notas_fisica += $estudiante->getNotaFisica();
                $suma_notas_programacion += $estudiante->getNotaProgramacion();

                if ($estudiante->getNotaMatematicas() > 9) {
                    $cantidad_aprobados_matematicas++;
                    $estudiante->aprobarMatematicas();
                } else {
                    $cantidad_aplazados_matematicas++;
                }

                if ($estudiante->getNotaFisica() > 9) {
                    $cantidad_aprobados_fisica++;
                    $estudiante->aprobarFisica();
                } else {
                    $cantidad_aplazados_fisica++;
                }

                if ($estudiante->getNotaProgramacion() > 9) {
                    $cantidad_aprobados_programacion++;
                    $estudiante->aprobarProgramacion();
                } else {
                    $cantidad_aplazados_programacion++;
                }

                if ($estudiante->getNotaMatematicas() > $nota_maxima_matematicas) {
                    $nota_maxima_matematicas = $estudiante->getNotaMatematicas();
                }

                if ($estudiante->getNotaFisica() > $nota_maxima_fisica) {
                    $nota_maxima_fisica = $estudiante->getNotaFisica();
                }

                if ($estudiante->getNotaProgramacion() > $nota_maxima_programacion) {
                    $nota_maxima_programacion = $estudiante->getNotaProgramacion();
                }
            }
            ?>

            <tr>
                <th scope="row">Matemáticas</th>
                <?php
                echo "<td>";
                try {
                    echo $suma_notas_matematicas / sizeof($alumnos_registrados);
                } catch (DivisionByZeroError) {
                    echo "0";
                }
                echo "</td>";

                echo "<td>";
                echo $cantidad_aprobados_matematicas;
                echo "</td>";

                echo "<td>";
                echo $cantidad_aplazados_matematicas;
                echo "</td>";

                echo "<td>";
                echo $nota_maxima_matematicas;
                echo "</td>";
                ?>
            </tr>

            <tr>
                <th scope="row">Física</th>
                <?php
                echo "<td>";
                try {
                    echo $suma_notas_fisica / sizeof($alumnos_registrados);
                } catch (DivisionByZeroError) {
                    echo "0";
                }
                echo "</td>";

                echo "<td>";
                echo $cantidad_aprobados_fisica;
                echo "</td>";

                echo "<td>";
                echo $cantidad_aplazados_fisica;
                echo "</td>";

                echo "<td>";
                echo $nota_maxima_fisica;
                echo "</td>";
                ?>
            </tr>

            <tr>
                <th scope="row">Programación</th>
                <?php
                echo "<td>";
                try {
                    echo $suma_notas_programacion / sizeof($alumnos_registrados);
                } catch (DivisionByZeroError) {
                    echo "0";
                }
                echo "</td>";

                echo "<td>";
                echo $cantidad_aprobados_programacion;
                echo "</td>";

                echo "<td>";
                echo $cantidad_aplazados_programacion;
                echo "</td>";

                echo "<td>";
                echo $nota_maxima_programacion;
                echo "</td>";
                ?>
            </tr>

            </tbody>
        </table>
    </div>
    <div class="tabla">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Estudiantes que aprobaron todo</th>
                <th scope="col">Estudiantes que solo aprobaron una(1) materia</th>
                <th scope="col">Estudiantes que solo aprobaron dos(2) materias</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $cantidad_aprobaron_todo = 0;
            $cantidad_aprobaron_una_materia = 0;
            $cantidad_aprobaron_dos_materias = 0;

            foreach ($alumnos_registrados as $estudiante) {
                $cantidad_aprobadas = $estudiante->aprobado_matematicas + $estudiante->aprobado_fisica + $estudiante->aprobado_programacion;

                if ($cantidad_aprobadas == 3) {
                    $cantidad_aprobaron_todo++;
                } elseif ($cantidad_aprobadas == 1) {
                    $cantidad_aprobaron_una_materia++;
                } elseif ($cantidad_aprobadas == 2) {
                    $cantidad_aprobaron_dos_materias++;
                }
            }
            ?>
            <tr>
                <?php
                echo "<td>";
                echo $cantidad_aprobaron_todo;
                echo "</td>";

                echo "<td>";
                echo $cantidad_aprobaron_una_materia;
                echo "</td>";

                echo "<td>";
                echo $cantidad_aprobaron_dos_materias;
                echo "</td>";
                ?>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</body>

<script>
    function lettersOnly(input) {
        var regex = /[^a-z ]/gi;
        input.value = input.value.replace(regex, "");
    }
</script>
</html>
