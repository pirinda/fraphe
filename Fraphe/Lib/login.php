<?php require "../html/appstartsession.php"; ?>
<!DOCTYPE html>
<html>
<?php require "../html/htmlhead.php"; ?>
<body>
  <div class="container">
    <h1>Iniciar sesión</h1>
    <form action="loginaction.php" method="post">
      <div class="form-group">
        <label for="username">Nombre de usuario o dirección de correo:</label>
        <input type="username" class="form-control" name="username" placeholder="tu nombre de usuario..." autofocus required>
      </div>
      <div class="form-group">
        <label for="userpswd">Contraseña:</label>
        <input type="password" class="form-control" name="userpswd" placeholder="tu contraseña..." required>
      </div>
      <div class="checkbox">
        <label><input type="checkbox"> Recordarme</label>
      </div>
      <button type="submit" class="btn btn-primary">Iniciar</button>
    </form>
  </div>
  <?php require "appfooter.php"; ?>
</body>
</html>
