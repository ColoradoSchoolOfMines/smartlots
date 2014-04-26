<?php
  
  require 'validate_credentials.php';

  $username = $_SERVER['PHP_AUTH_USER'];
  $password = $_SERVER['PHP_AUTH_PW'];

  if (!validate($username, $password)) {
    header('WWW-Authenticate: Basic realm="Administrator Realm"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Not authorized");
  }

?>