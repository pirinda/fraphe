<?php
//------------------------------------------------------------------------------
// start session:
if (!isset($_SESSION)) {
    session_start();
}
// bootstrap Fraphe:
require $_SESSION["rootDir"] . "Fraphe/fraphe.php";
//------------------------------------------------------------------------------

use Fraphe\App\FApp;
use Fraphe\App\FAppConsts;
?>
<!DOCTYPE html>
<html>
<?php echo FApp::composeHtmlHead(); ?>
<body>
  <div class="container">
    <h3>Iniciar sesión</h3>
    <?php
    if (!empty($_SESSION[FAppConsts::USER_LOGIN_MSG])) {
        echo '<div class="alert alert-danger alert-dismissible">';
        echo '<a href="#" class="close" data-dismiss="alert" aria-label="cerrar">&times;</a>';
        echo '<strong>¡Error de acceso!</strong> ' . $_SESSION[FAppConsts::USER_LOGIN_MSG];
        echo '</div>';
    }
    ?>
    <form action="loginaction.php" method="post">
      <div class="form-group">
        <label for="username">Nombre de usuario:</label>
        <input type="text" class="form-control" name="username" placeholder="tu nombre de usuario..." autofocus required>
      </div>
      <div class="form-group">
        <label for="userpswd">Contraseña:</label>
        <input type="password" class="form-control" name="userpswd" placeholder="tu contraseña..." required>
      </div>
      <div class="checkbox">
        <label><input type="checkbox"> Recordarme</label>
      </div>
      <button type="submit" class="btn btn-sm btn-primary">Iniciar</button>
      <?php
      echo '<a href="' . $_SESSION[FAppConsts::ROOT_DIR_WEB] . 'index.php?" class="btn btn-sm btn-danger" role="button">Cancelar</a>';
      ?>
    </form>
  </div>
  <?php echo Fraphe\App\FApp::composeFooter(); ?>
</body>
</html>
