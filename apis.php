<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");


$errors = array();
$successes = array();
if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST') {
   $api = $_POST['apiname'];
   $group = $_POST['group'];
   
   $errors = $loggedInUser->createNewApi($api, $group);
   
   if ($errors == null || empty($errors)) {
       header('HTTP/1.1 302 Found', true, 302);
       header("Location: apiload.php?api=" . $api);
       array_push($successes, 'New Api with name "'.$api.'" successfully created.');
   }
}


$newapiname = 'new-api';
$newapi = $newapiname;
$i = 0;
while (file_exists(__DIR__ . '/spec-files/'.$newapi.'.yaml')) {
   $i++;
   $newapi = $newapiname.'-'.$i;
}

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<div id='left-nav'>";

include("left-nav.php");

echo " 
</div>
<div id='main'>";

echo resultBlock($errors,$successes);


$apis = $loggedInUser->getApis();
$personal = $apis['personal'];
$group = $apis['group'];

echo "
<br/><h3>Personal</h3>
<div class='api' style='border: none'>
   <form method='post'>
      <input type='text' name='apiname' value='".$newapi."' size='7'/>
      <input type='hidden' name='group' value='personal'/>
      <input type='submit' value='Create New'/>
   </form>
</div>
";
foreach ($personal as $row) {
  echo "<div class='api'><a class='api' href='apiload.php?api=".$row['design']."'>".$row['design']."</a></div>\n";
}
foreach ($group as $key => $grp) {
  echo "
  <br/><h3>".$key."</h3>
  <div class='api' style='border: none;'>
      <form method='post'>
         <input type='text' name='apiname' value='".$newapi."' size='7'/>
         <input type='hidden' name='group' value='".$key."'/>
         <input type='submit' value='Create New'/>
      </form>
  </div>
  ";
  foreach ($grp as $row) {
     echo "<div class='api'><a class='api' href='apiload.php?api=".$row['design']."'>".$row['design']."</a></div>\n";
  }
}

echo "
</div> <!-- main -->
<div id='bottom'></div>
</div>
</body>
</html>";

?>
