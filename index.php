<?php
session_start();


    $colors = ["red", "yellow", "blue", "green", "black", "orange"];
    $numbers = [1, 2, 3, 4, 5, 6];

    if (!isset($_SESSION['combi'])) {
        $combi = [];
        createTable($combi, $colors, $numbers);
        $_SESSION['combi'] = $combi;
    } else {
        $combi = $_SESSION['combi'];
    } 

    $rowin = isset($_POST['rowin']) ? intval($_POST['rowin']) : null;
    $colin = isset($_POST['colin']) ? intval($_POST['colin']) : null;
    $row = isset($_POST['row']) ? intval($_POST['row']) : null;
    $col = isset($_POST['col']) ? intval($_POST['col']) : null;
    $message = "";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($rowin !== null && $colin !== null && $row !== null && $col !== null) {
            $message = "<p>Fila actual: $rowin, Columna actual: $colin</p>";
            $message .= "<p>Fila destino: $row, Columna destino: $col</p>";

            if (isset($_SESSION['combi'])) {
                $combi = $_SESSION['combi'];
                $message .= movement($combi, $col, $row, $colin, $rowin);
            } else {
                $message .= "<p>Error: No se encontró la tabla en sesión.</p>";
            }
        } else {
            $message .= "<p>Por favor, completa todos los campos del formulario.</p>";
        }
    }

function createTable(&$combi, $colors, $numbers): array {
    for ($i = 0; $i < count($colors); $i++) {
        for ($j = 0; $j < count($numbers); $j++) {
            assign($combi, $colors[$i], $numbers[$j]);
        }
    }
    return $combi;
}

function assign(&$combi, $color, $number) {
    $col = rand(0, 5);
    $row = rand(0, 5);

    if (!isset($combi[$row][$col])) {
        $combi[$row][$col]['color'] = $color;
        $combi[$row][$col]['number'] = $number;
    } else {
        assign($combi, $color, $number);
    }
}

function printTable($combi) {
    echo "<div style='display: flex; flex-direction: column; align-items: center;'>";
    for ($i = 0; $i < count($combi); $i++) {
        $cont = 0;
        echo "<div style='display: flex;'>";
        for ($j = 0; $j < count($combi[$i]); $j++) {
            $color = $combi[$i][$j]['color'];
            $textColor = ($color === "black") ? "white" : "black";
            echo "<span style='display: inline-block; width: 20px; height: 20px; background-color: $color; color: $textColor; text-align: center; line-height: 20px; margin: 1px;'>";
            echo $combi[$i][$j]['number'];
            echo "</span>";
            $cont++;
            if ($cont == 24) {
                echo "</div><div style='display: flex;'>";
                $cont = 0;
            }
        }
        echo "</div>";
    }
    echo "</div>";
}
    function movement (array $combi, int $col, int $row, int $colin, int $rowin) {
        $col -= 1;
        $row -= 1;
        $colin -= 1;
        $rowin -= 1;
    
        if (isset($combi[$rowin][$colin]) && isset($combi[$row][$col])) {
            if (($row == $rowin || $colin == $col) && 
                ($combi[$rowin][$colin]['color'] == $combi[$row][$col]['color'] 
                || $combi[$rowin][$colin]['number'] == $combi[$row][$col]['number'])){
                    return "Movimiento valido!";
            } else {
                return "Movimiento no valido! (Diferente color/numero)";
            }
        } else {
            return "Posición inválida en el tablero.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RoboApp 🤖</title>
    <style>
        body{
            background-color: #74acdf;
        }
        header{
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 7px;
            padding: 2px;
            border-bottom: 2px solid black;
        }
        .container{
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }
        form, article{
            width: 45%;
        }
        article{
            margin-left: 90px;
            margin-top: 30px;
            width: 80%;
            padding: 5px;
            border: 1px solid black;
        }
        form{
            margin-top: 4px;
            width: 29%;
        }
        input{
            width: 40px;
            margin: 2px;
        }
        #submit{
            width: 70px;
            margin-left: 60px;
        }
        .message{
            margin-right: 83px;
        }
    </style>
</head>
    <header>RoboApp 🤖</header>
    <div class="container">
        <form method="POST" action="">
            <label for="rowin">Introduce la fila actual: </label>
            <input type="text" id="rowin" name="rowin" value=""><br>
            <label for="colin">Introduce la columna actual: </label>
            <input type="text" id="colin" name="colin" value=""><br>
            <label for="rowin">Introduce la fila fin: </label>
            <input type="text" id="rowin" name="row" value=""><br>
            <label for="col">Introduce la columna fin: </label>
            <input type="text" id="col" name="col" value=""><br>
            <input id="submit" type="submit" value="Submit">
        </form>
        <?php printTable($combi); ?>
        <div class="message">
        <?php echo $message; ?>
        </div>
    </div>
        <article>
            <h3>Reglas del Juego</h3>
            <ul>
                <li>El tablero tiene 6 filas y 6 columnas, numeradas del 1 al 6.</li>
                <li>Ingresa las posiciones de fila y columna (del 1 al 6) para mover tu ficha.</li>
                <li>El movimiento será válido si la celda de destino tiene el mismo color o número que la celda original.</li>
            </ul>
        <p><strong>Ejemplo:</strong> Si tu ficha está en la fila 2, columna 3 (índice 1, 2) y quieres moverla a la fila 1, columna 2 (índice 0, 1), el movimiento será interpretado por el sistema de acuerdo a la lógica mencionada.</p>
        <p>¡Buena suerte y que te diviertas jugando!</p>
        </article>
</body>
</html>