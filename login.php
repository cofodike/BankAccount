<?php
	include "dbconfig.php";
	$cName=$cStreet= $cCity= $cZip=$cImg= $cid= $cAge = $userID= "";
?>
<?php 
        // if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $con = mysqli_connect($host,$username,$password,$dbname)
            or die("<br>cannot connect to db\n");
            $myusername = mysqli_real_escape_string($con,$_POST['username']);
            $mypassword = mysqli_real_escape_string($con,$_POST['password']); 
        // }    
      // if (!isset($_POST['variable']))
      $sql = "SELECT id, name, password from CPS3740.Customers where login = '$myusername'";
      $result = mysqli_query($con,$sql);
      $count = mysqli_num_rows($result);
		
      if($count == 0) {
         echo "Login $myusername doesn’t exist in the database\n";
         echo "<p> <a href='index.html'> Login </a>";
            die();
      }else if ($count > 1) {
      	echo "More than one user login\n";
      }
      else{
      		$row = mysqli_fetch_array($result);
      		if ($row['password']==$mypassword) {
      				$cid = $row["id"];
      				$cName = $row["name"];
                    $cPass = $row["password"];
      				setcookie("id",$cid, time() + 60 * 60);
      				setcookie("c_user",$cName, time() + 60 * 60);
      				$sql = "SELECT id, name, password, TIMESTAMPDIFF(YEAR, DOB, CURDATE()) AS age, img, street, city, zipcode from CPS3740.Customers where login = '$myusername'";
							$result = mysqli_query($con,$sql);
							$count = mysqli_num_rows($result);
							if($count == 0) {
         					echo "Login $myusername doesn’t exist in the database\n";
         				}
         				else{
                            // $cookie_id = $_COOKIE["id"];
         					while($row = $result->fetch_assoc()) {
      							$cStreet = $row['street'];
      							$cCity = $row['city'];
      							$cZip = $row['zipcode'];
      							$cImg = $row['img'];
      							$cAge = $row['age'];
         					}
         			} 
      		}
      		else{
      			echo "Login failed (user exists, but wrong password!)";
      			echo "<p> <a href='index.html'> Login </a>";
      			die();
      		}
      }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        span {
            font-weight: bold;
        }
        table, th, td {
            border:1px solid black;
        }
    </style>
</head>

<body>
    <h1>Home page</h1>
    <a href="logout.php">User logout</a>

    <p>Your IP: <?php echo $_SERVER["REMOTE_ADDR"]; ?></p>
    <p>Your Browser and OS: <?php echo $_SERVER["HTTP_USER_AGENT"]; ?></p>
    <?php
    if (!empty($_SERVER['HTTP_CLIENT_IP'])){ 
		$ip = $_SERVER['HTTP_CLIENT_IP']; }
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		 $ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		} 
		 else { 
		 	$ip = $_SERVER['REMOTE_ADDR']; 
		 }
		 	 $IPv4= explode(".",$ip);
		echo "Your IP: $ip\n";
		if($IPv4[0]==10 || $IPv4[0]==131 && $IPv4[1]==125){ 
			echo "<br>You are from Kean Unversity.\n"; }
		else {
		 echo "<br>You are NOT from Kean Unversity.\n"; }
    ?>
    <p> Welcome Customer: <b><?php echo $cName; ?></b>
    <p>Age: <?php echo $cAge; ?></p>
    <p>Address: <?php echo "$cStreet, $cCity $cZip"; ?></p>
    <img src="data:image/jpg;base64, <?php echo base64_encode($cImg); ?>" alt="">

    <table>
        <thead>
            <tr>
                <td>ID</td>
                <td>Code</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Source</td>
                <td>Date Time</td>
                <td>Note</td>
            </tr>
        </thead>
        <tbody>
            <?php 
                // $query = "SELECT m.id, cid, code, type, amount,s.name AS source, mydatetime, note from CPS3740_2021F.Money_ofodikch m, CPS3740.Customers c, CPS3740.Sources s where m.cid = c.id AND c.name = '$cName' AND s.id = m.sid ORDER BY mydatetime ";
            // if (isset($_COOKIE["id"])) {
            //         $userId = $_COOKIE["id"];
            //         // echo $userId;
            //     }
                $query2 = "SELECT M.id, M.code, M.cid, M.type, M.amount, M.mydatetime, M.note, S.name as source from CPS3740_2021F.Money_ofodikch M, CPS3740.Sources S where M.sid=S.id and M.cid=$cid ORDER BY mydatetime"; 
                // echo $query2;
            $result2 = mysqli_query($con, $query2);
            $cBalance = 0;
            if ($result2) {
                while ($row = mysqli_fetch_array($result2)) {
                    if ($row["type"] == "D") {
                        $cBalance += $row["amount"];
                    } else {
                        $cBalance -= $row["amount"];
                    } 
            ?>

            <tr>
                <td><?php echo $row["id"]; ?></td>
                <td><?php echo $row["code"]; ?></td>
                <td>
                    <?php 
                        echo $row["type"] == "D" ? "Deposit" : "Widthdraw";
                    ?> 
                </td>
                <td style="color: <?php echo $row["type"] == "D" ? "blue" : "red"; ?>;"><?php 
                    echo $row["type"] == "D" ? $row["amount"] : "-" . $row["amount"]; 
                ?>
                </td>
                <td><?php echo $row["source"]; ?></td>
                <td><?php echo $row["mydatetime"]; ?></td>
                <td><?php echo $row["note"]; ?>
                    
                </td>
                    </tr>
             <?php
                }
            }
            ?>   
        </tbody>
    </table>
    <p>Total:
        <span style="color: <?php echo $cBalance < 0
                                ? "red"
                                : "blue"; ?>;">
            <?php echo $cBalance; ?>
        </span>
    </p>
    <div>
        <a href="add_transaction.php">
        <button>Add transaction</button>
        </a>
        <a href="display_transaction.php">Display and update transaction</a>
        <a href="display_stores.php">Display stores</a>
    </div>
    <div>
        <form action="search_transaction.php" method="GET">
            <label for="search">Keyword
                <input type="text" name="keyword" id="search">
                <button type="submit">Search transaction</button>
            </label>
        </form>
    </div>
</body>

</html>
