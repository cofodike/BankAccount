<html>
<head>
<?php
	if (!isset($_COOKIE['c_user'])){
		echo "You must be logged in to access this page!";
		echo " <p> <a href='login.html'> </p>" ;
		die();
	}
	include "dbconfig.php";
	$con = mysqli_connect($host,$username,$password,$dbname)
		or die("<br>cannot connect to db\n");
	
	$cUsername = $_COOKIE['c_user'];
	$cid = $_COOKIE['id'];
	// setcookie("id",$cid, time() + 60 * 60);
 //    setcookie("c_user",$cName, time() + 60 * 60);

	$query = "SELECT m.type, m.amount FROM CPS3740_2021F.Money_ofodikch m, CPS3740.Customers c WHERE m.cid = c.id AND c.id = $cid";
	$result = mysqli_query($con, $query);
	// echo $query;
	$cBalance = 0;

	if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        // calculate customers total balance
        if ($row["type"] == "D") {
            $cBalance += $row["amount"];
        } else {
            $cBalance -= $row["amount"];
        }
    }
}
?>
</head>
<body>
	<br>
<p><b>Add Transaction</b></p>
<p>
	<b><?php echo $cUsername; ?></b>
</p>
<p>
	Current balance: <b><?php echo $cBalance; ?></b>.
</p>

<form action="insert_transaction.php" method="POST">
	<div>
		<label>Transaction Code: </label>
		<input type="text" name="tCode" id="code" >
	</div>
	<div>
		<input type="radio" name="tType" id="deposit" value="D" >
        <label for="deposit">Deposit</label>
		<input type="radio" name="tType" id="withdraw" value="W" >
		<label for="withdraw">Withdraw</label>
	</div>
	<div>
        <label for="amount">Amount:</label>
        <input type="text" name="tAmount" id="amount">
        <input type="hidden" name="balance" >
    </div>
    <div>
            <label for="source">Select a Source:</label>
            <select name="tSource" id="tSource" >
                <option value=""></option>
                <?php
                $sql = "SELECT id,name FROM CPS3740.Sources";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_array($result)) {
                     ?>
                        <option value=<?php echo $row["id"]; ?>>
                            <?php echo $row["name"]; ?>
                        </option>
                <?php 
            }}?>
            </select>
        </div>
        <div>
            <label for="note">Note:</label>
            <input type="text" name="tNote" id="tNote">
        </div>

        <button>Submit</button>
</form>
</body>
</html>


