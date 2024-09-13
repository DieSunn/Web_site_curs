<?php
session_start();

        $_SESSION["usernmame"] = null;
        $_SESSION["password"] = null;
        header("Location: index.php")
?>