<?php
	if (!isset($_COOKIE['c_user'])){
		echo "You must be logged in to access this page!";
		echo " <p> <a href='login.html'> </p>" ;
		exit();
	}
	else{
		include "dbconfig.php";
		$con = mysqli_connect($host,$username,$password,$dbname)
		or die("<br>cannot connect to db\n");
		$cUser = $_COOKIE['c_user'];
        $userId = $_COOKIE["id"];
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update, Delete and Display Stores.</title>
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
<?php 
	$sql = "SELECT m.id, code, cid, type, amount, mydatetime, note, name AS source FROM CPS3740_2021F.Money_ofodikch m, CPS3740.Sources s     
        WHERE m.cid = $userId AND m.sid = s.id;";
		$result = mysqli_query($con, $sql);
        $cBalance = 0;
?>
<a href="logout.php">User logout</a>
<p> You can only update <strong>Note</strong> column.</p>
<form action="update_transaction.php" method="post"> 
    <table >
        <thead border="1" style="font-weight:bold">
            <tr>
                <td>ID</td>
                <td>Code</td>
                <td>Amount</td>
                <td>Type</td>
                <td>Source</td>
                <td>Date Time</td>
                <td>Note</td>
                <td>Delete</td>
            </tr>
        </thead>
        <tbody>
<!-- echo "
<tr>
<td> blah blah
<tr>
"
"blah " . $row['somecolumn'] . " 2blah"
-->
<!-- (15 pts) Display update function - If customer clicks on “Display transaction” link, a program named
“display_transcation.php” should show the logged in customer’s transactions in your Money_xxxx table on a web
page as shown in Figure 5.
6.1 _____ (5 pts) Only the Note field is updateable and you should highlight it in yellow. All other columns (code,
Source name, amount, operation, and datetime are Non-updateable fields as shown in Figure 5.
6.2 _____ (5 pts) Add a delete column that has the HTML checkbox for each record after the Note column.
6.3 _____ (5 pts) You should display Source name, NOT source id. -->
            <?php 
                if ($result){
                    if(mysqli_num_rows($result) > 0){
                        $count = $index = 0;
                        while ($row = mysqli_fetch_array($result)){
                            $color = 'black';
                            if ($row['type'] == 'D'){
                                $cBalance += $row['amount'];
                                 $color = 'DarkBlue';
                            }else{
                                $cBalance -= $row['amount'];
                                 $color = 'DarkRed';
                            }

// echo '<td>'.$row['type'] == 'D' ? 'Deposit' : 'Withdraw' . '</td>';
                            // $color = 'black';
                            // if ($row['sex']=='M')
                            //     $color = 'blue';
                            // else if ($row['sex']=='F')
                            //     $color = 'red';
                            // echo '<td><span style="color:'.$color.';">'.$row['sex'].'</span></td>';

                            echo '<tr>';
                            echo '<td>'. $row['id'] .'</td>';
                            echo '<td>'. $row['code'] .'</td>';

                            echo '<td style= "color:$color">'. $row['amount'] . '</td>';
                            if ($row['type'] == 'D'){
                                echo '<td>' . "Deposit" . '</td>';
                            }else{
                                echo '<td>' . "Withdraw" . '</td>';
                            }
                            echo '<td>'. $row['source'] . '</td>';
                            echo '<td>'. $row['mydatetime'] . '</td>';
                            // echo '<td>'. $row['source'] . '</td>';
                            // $cName=$cStreet= $cCity= $cZip=$cImg= $cid= $cAge = "";
                            // echo '<td>' . $row['type'] == 'D' ? 'blue' : 'red' . '</td>';
                            // echo '</tr>';

//                             <form>
//   <label for="username">Username:</label><br>
//   <input type="text" id="username" name="username"><br>
//   <label for="pwd">Password:</label><br>
//   <input type="password" id="pwd" name="pwd">
// </form>
                            // echo 
                            // '<td>'. 
                            // $myv = $row['note'];
                            // "<input style = 'background-color: yellow' type='text' name='note[]'
                            // 'value= $myv'>" .

                            // '</td>';

                           echo '<td>' . '<input style= "background-color: yellow" "type=text" name="updates[]" value="' . $row['note'] . '" >'. 
                           '<input type="hidden" name="id[]" value = "' . $row['id'] . '" >' . 

                           '</td>';


                           echo '<td>'.
                           '<input type="checkbox" name="deletes[]" value="' . $row['id'] .'">' . '</td>';
                           '</tr>';
                        }
                    }else {
                        echo "<p><a href='add_transaction.php'>Add transaction</a></p>";
                        die("No records found");
                    }
                }else {
                    echo "<p><a href='login.php'>Go back</a></p>";
                    die("Error with connection");
                }
            ?>
            
        </tbody>
    </table>
    <p>Total balance: <?php echo $cBalance; ?></p>
        <input type="submit" value="Update Transaction">
</form>
</body>
</html>