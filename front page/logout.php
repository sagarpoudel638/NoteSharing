<?php
session_start();
session_destroy();
header("Location: /project-main/login/login.html");
exit();
?>
