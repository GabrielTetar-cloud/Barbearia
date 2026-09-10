```php
<?php

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "barbearia";

$conexao = new mysqli($host, $usuario, $senha, $banco);

if ($conexao->connect_error) {
    echo "erro";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "erro";
    exit;
}

$dia = intval($_POST["dia"] ?? 0);
$hora = intval($_POST["hora"] ?? 0);
$corte = intval($_POST["corte"] ?? 0);
$prof = intval($_POST["prof"] ?? 0);


/* Verifica horário */

$sql = "SELECT id
        FROM agendamentos
        WHERE dia = ?
        AND hora = ?
        AND prof = ?";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    echo "erro";
    exit;
}

$stmt->bind_param("iii", $dia, $hora, $prof);
$stmt->execute();

$resultado = $stmt->get_result();


/* Horário ocupado */

if ($resultado->num_rows > 0) {

    echo "ocupado";

    $stmt->close();
    $conexao->close();

    exit;
}

$stmt->close();


/* Grava o agendamento */

$sql = "INSERT INTO agendamentos
        (dia, hora, corte, prof)
        VALUES (?, ?, ?, ?)";

$stmt = $conexao->prepare($sql);

if (!$stmt) {
    echo "erro";
    exit;
}

$stmt->bind_param("iiii", $dia, $hora, $corte, $prof);


if ($stmt->execute()) {

    echo "sucesso";

} else {

    echo "erro";

}


$stmt->close();
$conexao->close();

?>
```

