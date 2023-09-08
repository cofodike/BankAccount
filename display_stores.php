<?php
if (!isset($_COOKIE["c_user"])) {
    echo "You must be logged in to access this page";
    echo "<p>
                <a href='index.html'>Login</a>
         </p>
        ";
    exit();
}else{
        include "dbconfig.php";
        $con = mysqli_connect($host,$username,$password,$dbname)
        or die("<br>cannot connect to db\n");
        $cUser = $_COOKIE['c_user'];
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
</head>
<body>
    <p style="text-align: center;"> <b> The following stores are in the database </b></p>
    <table border="1" style="margin: 0 auto;" >
        <thead>
            <tr>
                <td>ID</td>
                <td>Name</td>
                <td>Address</td>
                <td>City</td>
                <td>State</td>
                <td>Zipcode</td>
                <td>Location(Latitude, Lognitude)</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT * FROM CPS3740.Stores WHERE (Name IS NOT NULL AND address IS NOT NULL AND address IS NOT NULL AND city IS NOT NULL AND State IS NOT NULL AND Zipcode IS NOT NULL AND latitude IS NOT NULL AND longitude IS NOT NULL)";
            $result = mysqli_query($con, $query);
            ?>

            <?php
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = $result -> fetch_assoc()){
                            echo '<tr>';
                            echo '<td>'.$row['sid'].'</td>';
                            echo '<td>'.$row['Name'].'</td>';
                            echo '<td>'.$row['address'].'</td>';
                            echo '<td>'.$row['city'].'</td>';
                            // echo '<td>'.$row['type'] == 'D' ? 'Deposit' : 'Withdraw' . '</td>';
                            // $color = 'black';
                            // if ($row['sex']=='M')
                            //     $color = 'blue';
                            // else if ($row['sex']=='F')
                            //     $color = 'red';
                            // echo '<td><span style="color:'.$color.';">'.$row['sex'].'</span></td>';
                            echo '<td>'.$row['State'].'</td>';
                            echo '<td>'.$row['Zipcode'].'</td>';
                            echo '<td>'.$row['latitude']. $row['longitude'];'</td>';
                            echo '</tr>';
                        }
                }
            }
            ?>
        </tbody>
    </table>
    <!-- <a href="index.html">Homepage</a> -->
</body>

</html>