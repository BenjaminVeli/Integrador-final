<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preguntas y Alternativas</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/preguntas.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="menu" container>
            <img class="menu__logo" src="../img/logo.png" alt="">
            <nav class="navbar">
                <div class="menu-1">
                    <ul>
                        <li><i class='bx bx-log-out bx-md'></i><a href="logout.php">Desconectarse</a></li>
                    </ul>
                </div>
            </nav>
        </div>  
    </header>

    <div class="preguntas--container">
    <?php
        // Inicializar el puntaje y el historial de puntajes
        if (!isset($_SESSION['puntaje'])) {
            $_SESSION['puntaje'] = 0;
        }

        if (!isset($_SESSION['historial_puntajes'])) {
            $_SESSION['historial_puntajes'] = array();
        }

        // Inicializar el historial de respuestas
        if (!isset($_SESSION['historial_respuestas'])) {
            $_SESSION['historial_respuestas'] = array();
        }

        $servername = "localhost";
        $username = "root";
        $password = "usbw";
        $dbname = "preguntados";

        // Crear conexión
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Verificar conexión
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Obtener los datos de la pregunta actual
            $pregunta_id = $_POST["pregunta_id"];
            $respuesta_seleccionada = $_POST["respuesta_seleccionada"];

            // Consultar la respuesta correcta de la pregunta actual
            $sql_respuesta_correcta = "SELECT opcion FROM alternativas WHERE pregunta_id = $pregunta_id AND es_correcta = 1";
            $result_respuesta_correcta = $conn->query($sql_respuesta_correcta);

            if ($result_respuesta_correcta->num_rows > 0) {
                $row_respuesta_correcta = $result_respuesta_correcta->fetch_assoc();
                $respuesta_correcta = $row_respuesta_correcta["opcion"];

                $respuesta_actual = array(
                    'pregunta_id' => $pregunta_id,
                    'respuesta_seleccionada' => $respuesta_seleccionada,
                    'respuesta_correcta' => $respuesta_correcta
                );

                if ($respuesta_seleccionada == $respuesta_correcta) {
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Respuesta correcta',
                        });
                        </script>";
                    $_SESSION['puntaje'] += 400;
                    $respuesta_actual['es_correcta'] = true;
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Respuesta incorrecta',
                        });
                        </script>";
                    $respuesta_actual['es_correcta'] = false;
                }

                // Guardar la respuesta actual en el historial de respuestas
                $_SESSION['historial_respuestas'][] = $respuesta_actual;
            }
        }

        // Obtener el ID de la categoría seleccionada
        if (isset($_GET["categoria_id"])) {
            $categoria_id = $_GET["categoria_id"];
        } else {
            // Si no se proporciona una categoría específica, obtener una categoría aleatoria
            $categoria_aleatoria = $categorias[array_rand($categorias)];
            $categoria_id = $categoria_aleatoria["id"];
        }


        // Obtener el contador de pregunta actual
        $contador_pregunta = isset($_POST["contador_pregunta"]) ? $_POST["contador_pregunta"] : 0;

        // Consulta de todas las preguntas de la categoría seleccionada
        $sql_preguntas = "SELECT id, texto FROM preguntas WHERE categoria_id = $categoria_id ORDER BY id";
        $result_preguntas = $conn->query($sql_preguntas);

        if ($result_preguntas->num_rows > 0) {
            $preguntas = $result_preguntas->fetch_all(MYSQLI_ASSOC);

            if ($contador_pregunta < count($preguntas)) {
                $pregunta_actual = $preguntas[$contador_pregunta];
                $pregunta_id = $pregunta_actual["id"];
                $pregunta_texto = $pregunta_actual["texto"];

                // Consulta de las alternativas de la pregunta actual
                $sql_alternativas = "SELECT id, opcion FROM alternativas WHERE pregunta_id = $pregunta_id";
                $result_alternativas = $conn->query($sql_alternativas);

                if ($result_alternativas->num_rows > 0) {
                    echo "<p class='enunciado-pregunta'>$pregunta_texto</p>";
                    echo "<form id='alternativas-form' action='' method='POST'>";
                    echo "<input type='hidden' name='categoria' value='$categoria_id'>";
                    echo "<input type='hidden' name='contador_pregunta' value='".($contador_pregunta + 1)."'>";
                    echo "<input type='hidden' name='pregunta_id' value='$pregunta_id'>";
                    echo "<div class='alternativas-container'>";
                    while ($row_alternativa = $result_alternativas->fetch_assoc()) {
                        $alternativa_id = $row_alternativa["id"];
                        $alternativa_texto = $row_alternativa["opcion"];
                            
                        echo "<div class='enunciado-alternativa-container'>";
                        echo "<label class='enunciado-alternativa'><input type='radio' name='respuesta_seleccionada' value='$alternativa_texto' class='radio-hidden' onclick='submitForm()'>$alternativa_texto</label>";
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</form>";
                }
            } else {
                echo "<div class='historial-puntaje-container'>";

                echo "<p class='historial-puntaje-txt'>Resumen del juego</p>";
                echo "<ul>";
                    foreach ($_SESSION['historial_puntajes'] as $index => $historial) {
                    $puntaje = $historial['puntaje'];


                    echo "<li>";
                    echo "<p class='puntos-obtenidos'>$puntaje puntos obtenidos</p>";
                    echo "</li>";
                    }

                    echo "<div class='historial-respuestas-container'>";
                        foreach ($_SESSION['historial_respuestas'] as $index => $respuesta) {
                            $pregunta_id = $respuesta['pregunta_id'];
                            $respuesta_seleccionada = $respuesta['respuesta_seleccionada'];
                            $respuesta_correcta = $respuesta['respuesta_correcta'];
                            $es_correcta = $respuesta['es_correcta'];
                            $pregunta_texto = $preguntas[$index]['texto'];

                            echo "<li>";
                            echo "<p class='pregunta-txt'>$pregunta_texto</p>";
                            echo "<p class='alternativas-txt'>Respuesta seleccionada: $respuesta_seleccionada</p>";
                            echo "<p class='alternativa-correcta-txt'>Respuesta correcta: $respuesta_correcta</p>";
                            if ($es_correcta) {
                                echo "<p class='respuesta-correcta'>400 puntos obtenidos</p>";
                            } else {
                                echo "<p class='respuesta-incorrecta'>0 puntos obtenidos</p>";
                            }
                            echo "</li>";
                            }
                            echo "</ul>";

                    echo "</div>";

                    echo "<a class='button-inicio' href='inicio.php?reset=1'>Volver a jugar</a>";

                echo "</div>";
                }
            }

        // Cerrar conexión
        $conn->close();
    ?>
    </div>

    <script>
        // Obtener el formulario y las alternativas
        var alternativasForm = document.getElementById('alternativas-form');
        var alternativas = alternativasForm.elements.respuesta_seleccionada;
        // Agregar el evento de cambio a cada alternativa
        for (var i = 0; i < alternativas.length; i++) {
        alternativas[i].addEventListener('change', function() {
            alternativasForm.submit();
            });
        }
    </script>
    
    <script src="../js/preguntas.js"></script>
    <footer class="footer__container ">
        <div class="footer__content ">
        </div>
        <div class="footer__websites ">
            <div class="footer__websites__content ">
                <div class="footer__navSection ">
                    <span class="footer__nav-title-text "><img src="../svg/facebook.svg " alt=" " class="social--svg ">
                    <span class="footer_nav-title-label ">FACEBOOK</span>
                    </span>
                </div>
                <div class="footer__navSection ">
                    <span class="footer__nav-title-text "><img src="../svg/instagram.svg " alt=" "  class="social--svg ">
                    <span class="footer_nav-title-label ">INSTAGRAM</span>
                    </span>
                </div>

                <div class="footer__navSection ">
                    <span class="footer__nav-title-text "><img src="../svg/tiktok.svg " alt=" "  class="social--svg ">
                    <span class="footer_nav-title-label ">TIKTOK</span>
                    </span>
                </div>
                <div class="footer__navSection ">
                    <span class="footer__nav-title-text "><img src="../svg/twitter.svg " alt=" "  class="social--svg ">
                    <span class="footer_nav-title-label ">TWITTER</span>
                    </span>
                </div>

            </div>
        </div>
        <div class="footer__copyright ">
            <p>©2021- 2023</p>
            <h2>Tecsup</h2>
            <p>- Todos los Derechos Reservados.</p>
        </div>
    </footer>
</body>
</html>
