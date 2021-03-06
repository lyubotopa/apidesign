<?php
 require_once("models/config.php");
 if (!isUserLoggedIn()) {
   header("Location: login.php");
   die();
 } 
?>
<!doctype html>
  <head>
    <meta charset="utf-8">
    <title>Swagger Editor</title>
    <meta name="description" content="Swagger Editor">
    <meta name="viewport" content="width=device-width">
    <link rel="icon" type="image/png" href="./images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="./images/favicon-16x16.png" sizes="16x16" />
    <link rel="stylesheet" href="dist/styles.css">
    <link rel="stylesheet" href="styles/branding.css">
  </head>
  <body>
    <div class="total-wrapper" ui-view></div>
    <script src="dist/bundle.js"></script>
    <script src="scripts/branding.js"></script>
</body>
</html>
