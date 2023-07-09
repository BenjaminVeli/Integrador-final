<?php
require 'database.php';

if (isset($_GET['email'])) {
  $searchEmail = $_GET['email'];
  
  try {
    $query = $conn->prepare("SELECT id, user, email, age, number FROM users WHERE email = :email");
    $query->bindParam(':email', $searchEmail);
    $query->execute();
    
    $users = $query->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css">
    <title>Búsqueda de información</title>
    <link rel="icon" href="../img/logo.png">
</head>
<body>
<header>
  <div class="menu">
    <img class="menu__logo" src="../img/logo.png" alt="">
    <input type="checkbox" id="menu" />
  </div>
</header>

    <div class="container container--search">
        <?php if (isset($users) && count($users) > 0): ?>
            <h2>Resultados de la búsqueda:</h2>
            <table class="table table-hover">
                <thead>
                    <tr class="container--tr">
                        <th scope="col">Id</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Correo</th>
                        <th scope="col">Edad</th>
                        <th scope="col">Número</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo $user['user']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['age']; ?></td>
                            <td><?php echo $user['number']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($_GET['email'])): ?>
            <p>No se encontraron usuarios con el email proporcionado.</p>
        <?php endif; ?>

    <div class="row">
        <div class="col-auto">
            <a class="btn btn-danger" href="read_usuarios.php" ">Volver al CRUD</a>
        </div>
    </div>

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
</body>
</html>
