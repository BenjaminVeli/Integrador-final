<?php
session_start();

require 'database.php';

if (isset($_SESSION['user_id'])) {
  $records = $conn->prepare('SELECT id, user, password FROM users WHERE id = :id');
  $records->bindParam(':id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);

  $user = null;

  if (count($results) > 0) {
    $user = $results;
  }
}

if (isset($_GET['reset'])) {
  $_SESSION['historial_puntajes'] = array();
  $_SESSION['historial_respuestas'] = array();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Preguntados</title>
    <link rel="icon" href="../img/logo.png">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/iniciophp.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">

</head>

<body>

    <header>
        <div class="menu" container>
            <img class="menu__logo" src="../img/logo.png" alt="">
            <input type="checkbox" id="menu"/>
            <?php if(!empty($user)): ?>
                <div class="user-welcome">
                    <span class="span--user">Bienvenido <?= $user['user']; ?></span>
                </div>
            <?php endif; ?>
            <label for="menu" onclick="toggleMenu()">
                <img src="../svg/menu.svg" class="menu-icon" alt="" id="menuIcon">
                <img src="../svg/aspa.svg" alt="" class="close-icon" id="closeIcon">
            </label>
            <nav class="navbar">
                <div class="menu-1">
                    <ul>
                        <li><i class='bx bx-log-out bx-md'></i><a href="logout.php">Desconectarse</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

    <script src="../js/header.js"></script>

    <?php
        // Conexión a la base de datos
        $servername = "localhost";
        $username = "root";
        $password = "usbw";
        $dbname = "preguntados";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta de las categorías disponibles
        $sql = "SELECT id, nombre FROM categorias";
        $result = $conn->query($sql);

        $categorias = array();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $nombre = $row["nombre"];
                $categorias[] = array("id" => $id, "nombre" => $nombre);
            }
        }

        // Cerrar conexión
        $conn->close();

        if (isset($_POST['comenzar'])) {
            // Obtener una categoría aleatoria
            $categoria_aleatoria = $categorias[array_rand($categorias)];

            // Redirigir a preguntas_alter.php con el ID de la categoría seleccionada
            header("Location: preguntas_alter.php?categoria_id=" . $categoria_aleatoria['id']);
            exit();
        }
    ?>

    <div class="container--spin">
        <img src="../img/Tectrivia-PNG.png" class="img--tectrivia">
        <img src="../img/spin-gif.gif" class="img--spin">
        <form method="post">
            <?php if (count($categorias) > 0) : ?>
                <?php
                // Obtener una categoría aleatoria
                $categoria_aleatoria = $categorias[array_rand($categorias)];
                ?>
                <input type="hidden" name="categoria" value="<?php echo $categoria_aleatoria['id']; ?>">
                <input type="submit" name="comenzar" value="Comenzar" class="button--spin">
            <?php else : ?>
                <p>No hay categorías disponibles.</p>
            <?php endif; ?>
        </form>
    </div>

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



    