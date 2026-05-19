<?php
session_start();
session_destroy();
echo "Sesión destruida. <a href='index.php?controller=auth&action=showLogin'>Ir al login</a>";
?>