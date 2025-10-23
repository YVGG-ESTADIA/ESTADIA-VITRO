<?php
// Iniciar la sesión actual en PHP.
// Esto es necesario para poder acceder a cualquier variable de sesión.
session_start();

// Destruir la sesión actual.
// Esto elimina todas las variables de sesión registradas y finaliza la sesión.
// Es común usarlo para cerrar sesión de un usuario (logout).
session_destroy();

// Redirigir al usuario a la página "index.php".
// header() envía un encabezado HTTP para indicar una redirección.
// Después de llamar a header() normalmente se usa exit() para evitar ejecución adicional.
header("Location:index.php");


exit(); // aquí para detener la ejecución del script.

?>
