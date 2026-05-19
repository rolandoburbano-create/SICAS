<?php
session_start();
session_destroy();
echo "<h2>¡Sesión destruida con éxito!</h2>";
echo "<a href='index.php?controller=auth&action=showLogin'>Haz clic aquí para volver al Login</a>";
?>