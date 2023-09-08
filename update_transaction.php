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
	}
$recordsDeleted = $recordsUpdated = 0;
?>

<?php 
	if (isset($_POST["deletes"])) {
		// echo count($_POST["deletes"]);
		foreach ($_POST["deletes"] as $deleting_id) {
    // for($i = 0; $i < count($_POST["deletes"]); $i++){
    	// $deleting_id = $_POST["deletes"][$i];
        $query = "DELETE FROM CPS3740_2021F.Money_ofodikch WHERE id='$deleting_id'; ";
        $result = mysqli_query($con, $query);
        if (mysqli_affected_rows($con) > 0) {
            echo "<p>The Code $deleting_id has been deleted in the database.";
            $recordsDeleted++;
        }
        // // echo "<br>";
        // echo $query;
    }
    // if (isset($_POST["deletes"])) {
		// echo count($_POST["deletes"]);
		// foreach ($_POST["deletes"] as $deleting_id) {
    // for($i = 0; $i < count($_POST["deletes"]); $i++){
    	// $deleting_id = $_POST["deletes"][$i];
        // $query = "DELETE FROM CPS3740_2021F.Money_ofodikch WHERE id='$deleting_id'; ";
        // $result = mysqli_query($con, $query);
        // if (mysqli_affected_rows($con) > 0) {
        //     echo "<p>The Code $deleting_id has been deleted in the database.";
        //     $recordsDeleted++;
        // }
        // // echo "<br>";
        // echo $query;
    }
}
?>

<?php 
	if (isset($_POST["updates"])) {
		// echo count($_POST["updates"]);
		// echo count($_POST["deletes"]);
		foreach ($_POST["updates"] as $curr => $updated_note) {
		// for($i = 0; $i < count($_POST["updates"]); $i++){
			// echo $newNote;p
    // for($i = 0; $i < count($_POST["deletes"]); $i++){
    	// $deleting_id = $_POST["deletes"][$i];
		$updating_id = $_POST["id"][$curr];
        $query = "SELECT note, mydatetime FROM CPS3740_2021F.Money_ofodikch WHERE id='$updating_id'; ";
        $result = mysqli_query($con, $query);
        if ($result){
        	if (mysqli_affected_rows($con) > 0) {
        		$row =  mysqli_fetch_array($result);
        		if ($updated_note != $row["note"]) {
                    $query2 = "UPDATE CPS3740_2021F.Money_ofodikch SET note='$updated_note', mydatetime=now() WHERE id='$updating_id'";
                    $result = mysqli_query($con, $query2);
        //             $returnStatement = 'Error inserting transaction ';
		    		// $returnStatement .= mysqli_error($con);
		    		// echo $returnStatement;
		    		// echo $query2;
                    if (!$result) {
                        echo mysqli_error($con);
                        die();
                    }else{
                    	echo "<p>The code $updating_id has been updated in the database.";
                        $recordsUpdated++;
        				// echo $query;
                    }
                }
        }
        // // echo "<br>";
    }
}
}
?>
<?php 
if (isset($_POST["updates"]) && !isset($_POST["deletes"])) {
	// echo count($_POST["updates"]);
    echo 
    	"<p>Successfully updated: " . $recordsUpdated . " records</p>
        <p>
            <a href='display_transaction.php'>Go Back</a>
        </p>";
} elseif ( isset($_POST["deletes"]) && !isset($_POST["updates"]) ) {
	echo 
	"<p>Successfully deleted: " . $recordsDeleted . " records</p>
        <p>
            <a href='display_transaction.php'>Go Back</a>
        </p>";
} elseif ( isset($_POST["deletes"]) && isset($_POST["updates"]) ) {
	echo 
	"<p>Successfully updated: " . $recordsUpdated . " records 
        successfully deleted: " . $recordsDeleted . " records</p>
        <p>
            <a href='display_transaction.php'>Go Back</a>
        </p>";
} else{
	echo "none were updated or deleted.";
}
?>
