<html>
<body>

	<?php
    if(!isset($_COOKIE["c_user"])) {
          echo "Cookie named '" . $cName . "' is not set!";
          header("Location: index.html");
          exit();
    } 
    else {
         	unset($_COOKIE["id"]);
		    unset($_COOKIE["c_user"]);
		    setcookie("id", "", time() - 3600);
		    setcookie("c_user", "", time() - 3600);
		    header("Location: index.html");
    		exit();
    }
    ?>
</body>
</html>

<!-- setcookie("id",$cid, time() + 60 * 60);
setcookie("c_user",$cName, time() + 60 * 60); -->