<?php
 require_once("models/config.php");
 if (!isUserLoggedIn()) {
   header("Location: login.php");
   die();
 } 
 if (!isset($_SERVER["HTTP_REFERER"])) {
   header("Location: account.php");
   die();
 }
?>
<!doctype html>
  <head>
    <script>
       function inIframe() {
           var in = false;
           try {
              if(window.self !== window.top) {
                 return true;
              } 
           } catch (e) {
           }
           try {
              if (window.frameElement) {
                 return true;
              }
           } catch (e) {
           }
           return false;
       }
alert("exec"); 
       if (inIframe()) {
alert("in iframe");
           window.location.href("account.php");
       } 
    </script>
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

   <script>
SwaggerEditor.on('put-failure', function() {
  alert('There was something wrong with saving your document.');
});
SwaggerEditor.on('put-success', function() {
  alert('Document successfully saved.');
});
SwaggerEditor.on('code-change', function() {
  window.status='Code changed...' + new Date();
  document.title = 'Code changed...' + new Date();
});
   </script>
</body>
</html>
