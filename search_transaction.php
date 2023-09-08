<?php
	if (!isset($_COOKIE['c_user'])){
		echo "You must be logged in to access this page!";
		echo " <p> <a href='login.html'> </p>" ;
		exit();
	}
	include "dbconfig.php";
	$con = mysqli_connect($host,$username,$password,$dbname)
	or die("<br>cannot connect to db\n");
	$cUser = $_COOKIE['c_user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Transaction.</title>
    <style>
        span {
            font-weight: bold;
        }
    </style>
</head>
<?php 
	if(isset($_GET['keyword'])){
		$keyword = $_GET['keyword'];
		$query = '';
		if ($keyword == '*'){
				$query = "SELECT m.id, cid, code, type, amount,s.name AS source, mydatetime, note from CPS3740_2021F.Money_ofodikch m, CPS3740.Customers c, CPS3740.Sources s where m.cid = c.id AND c.name = '$cUser' AND s.id = m.sid";
		}
		else{
			$query = "SELECT m.id, cid, code, type, amount,s.name AS source, mydatetime, note from CPS3740_2021F.Money_ofodikch m, CPS3740.Customers c, CPS3740.Sources s where m.cid = c.id AND c.name = '$cUser' AND s.id = m.sid AND note LIKE '%$keyword%'";
		}
	}
	?>
	<!-- setting up the table header -->
<body>
	
	<?php 
		$cBalance = 0;
		$result = mysqli_query($con, $query);
		$num = mysqli_num_rows($result);
		if ($num > 0) {
        	$color = 'black';
			echo "The transactions in customer <b>" . $cUser . "</b> records that matched keyword <b>$keyword </b> are";
			//create rows only if results were found:
			//iterate thru the rows and get each row based on the columns
				echo "
				<table border='1'>
				<thead>
            <tr>
                <td>ID</td>
                <td>Code</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Date Time</td>
                <td>Note</td>
                <td>Source</td>
            </tr>
        </thead>";
        while ($row = mysqli_fetch_array($result)) {
        	echo '<tr>';
        	if ($row['type']=='D'){
        		$color = 'blue';
        	}else{
        		$color = 'red';
        	}
        	$cBalance += $row['amount'];
        	echo '<td>'.$row['id'].'</td>';
	        echo '<td>'.$row['code'].'</td>';
	        echo '<td>'.$row['type'].'</td>';
	        // echo "$color <br>";
	        echo '<td style="color:$color;"  >'.$row['amount'].'</td>';
	        // echo '<td>'.$row['type'] == 'D' ? 'Deposit' : 'Withdraw' . '</td>';
	        // $color = 'black';
	        // if ($row['sex']=='M')
	        //     $color = 'blue';
	        // else if ($row['sex']=='F')
	        //     $color = 'red';
	        // echo '<td><span style="color:'.$color.';">'.$row['sex'].'</span></td>';
	        echo '<td>'.$row['mydatetime'].'</td>';
	        echo '<td>'.$row['note'].'</td>';
	        echo '<td>'.$row['source'].'</td>';
	        echo '</tr>';
	    }
		} else {
			echo "No record found with the search keyword: ". $keyword;
		}

	?>
	<p> Total balance of searched keyword: <?php echo $cBalance ?> </p>
</body>
</html>