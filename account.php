<?php

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}
require_once("models/header.php");

echo "
<body>
<div id='wrapper'>
<div id='top'><div id='logo'></div></div>
<div id='content'>
<div id='left-nav'>";

include("left-nav.php");

echo "
</div>
<!-- <div id='main'> -->
<style>
*{margin:0;padding:0}
iframe {height:95%;width:87%;position: fixed;margin-right: 10px;}
</style>
<iframe src='/apis' frameborder='0'></iframe>

<!-- </div> -->
<div id='bottom'></div>
</div>
</body>
</html>";

?>
