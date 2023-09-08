<html>
<head>
<?php
	include "dbconfig.php";
	$con = mysqli_connect($host,$username,$password,$dbname)
		or die("<br>cannot connect to db\n");

	if (!isset($_COOKIE['c_user'])){
		echo "You must be logged in to access this page!";
		echo " <p> <a href='index.html'> </p>" ;
	}
	else{
		$cUser  = $_COOKIE['c_user'];
		$cid = $_COOKIE['id'];
	}
?>
</head>
	<br>
<body>
<?php 
	// define variables and set to empty values
	$tCode = $tType = $tAmount = $tSource = $tNote = "";
	$cBalance = 0;
	echo "<a href='logout.php'>User logout</a>";
	//cleaning the passed variables
	function test_input($data) {
		if (!isset($_POST[$data])){}
		else{
				$data = trim($data);
			  	$data = stripslashes($data);
			  	$data = htmlspecialchars($data);
			  	return $data;
		}
	}
?>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST["tCode"], $_POST["tType"], $_POST["tAmount"], $_POST["tSource"], $_POST["tNote"])){
 	$tCode = $_POST["tCode"];
	$tType = strtoupper($_POST["tType"]);
 	$tSource = $_POST["tSource"];
 	$tNote = $_POST["tNote"];
 	$tAmount = $_POST["tAmount"];
 	
			 
 if (!is_numeric($tAmount)) {
	    echo "<p><a href='add_transaction.php'>Go back</a></p>";
	    die("Amount must be a numeric value");
	} elseif ($tAmount <= 0) {
	    echo "<p><a href='add_transaction.php'>Go back</a></p>";
	    die("Amount must be greater than 0");
	}
	}else{
		echo "<br>";
		echo "<div> Fill all fields please! </div>";
		echo "<p><a href='add_transaction.php'>Go back</a></p>";
				die();
	}
}
?>
<?php
	if (is_numeric($tAmount)){
		// echo "good amount";
    	if ($tAmount <=0 ){
    		echo "<p><a href='add_transaction.php'>Go back</a></p>";
    		die("Amount must be a value > 0");
    	}
    }
    // else{
    // 	echo "<p><a href='add_transaction.php'>Go back</a></p>";
    // 	die("Amount is not set!");
    // }
?>
<?php
	$query = "SELECT code,type,amount FROM CPS3740_2021F.Money_ofodikch WHERE cid = $cid";
	$result = mysqli_query($con, $query);
	//$cUsername = $_COOKIE['c_user'];
	//$cid = $_COOKIE['id'];
	//setcookie("id",$cid, time() + 60 * 60);
 	//setcookie("c_user",$cName, time() + 60 * 60);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if ($tCode == $row["code"]) {
                echo "<p><a href='add_transaction.php'>Go back</a></p>";
                die("Transaction code must be unique");
            }
            // echo $row["type"];
            if ($row["type"] == "D") {
                $cBalance += $row["amount"];
            } else {
                $cBalance -= $row["amount"];
            }
        }
    }
}
?>

<?php 
	if ($tType == "W"  && $tAmount > $cBalance) 
	{
	    echo "<p><a href='add_transaction.php'>Go back</a></p>";
	    die("Amount is greater than current balance");
	}
	else {
		$query = "INSERT INTO CPS3740_2021F.Money_ofodikch VALUES ('id','$tCode','$cid', '$tSource', '$tType', '$tAmount', curdate(), '$tNote');";
		// echo $query . "<br>";
		$result = mysqli_query($con, $query);

		if ($result) {
			// $tType = strtolower($tType);
		    if ($tType == "W") {
		        $cBalance -= $tAmount;
		    } else {
		        $cBalance += $tAmount;
		    }
		    // <p><a href='logout.php'>Logout</a></p>
	        	echo "<p>New balance: $cBalance</p>";
    			die("Successfully inserted transaction!");
    		}
    		else
    		{
    			$returnStatement = 'Error inserting transaction ';
    			$returnStatement .= mysqli_error($con);
    			echo "<p><a href='add_transaction.php'>Go back</a></p>";
    			die($returnStatement);
    		}
    		mysqli_free_result($result);
    		mysqli_close($con);
    	}
	
?>
</body>
</html>



