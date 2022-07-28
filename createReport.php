<?php
include("dbConnection.php");
date_default_timezone_set("Asia/Kabul");
// prevent opening without login
if ( session_id() === "" ) { session_start(); }
if(!(isset($_SESSION['name']) and isset($_SESSION['pass']))){
    header('location:login.php');
}

// --------------------------------------------
// insert the medicines which are expired into expire_medicine talbe
$select_columns = mysqli_query($conn,"SELECT exp_date, purchase_amount,purchase_price FROM purchase
            INNER JOIN medicine ON purchase.med_id = medicine.med_id WHERE exp_date < CURDATE();");

if(mysqli_num_rows($select_columns) > 0){
    while ($columns = mysqli_fetch_assoc($select_columns)){
        // store each column in a variable 
        $expired_date = $columns['exp_date'];
        $amount = $columns['purchase_amount'];
        $whole_sale_price = $columns['purchase_price'];

        // insert data
        mysqli_query($conn,"INSERT INTO expire_medicine VALUES
         (NULL,'$expired_date',$amount,$whole_sale_price);");
        
        // delete expired medicine from database
        mysqli_query($conn,"DELETE FROM medicine WHERE exp_date = '$expired_date ' ");
    }
}

// -------------------------------------------------




// -------------------------------------------------
$benefit = array();
if(isset($_REQUEST['date'])){
    //Get the date which is sent
    $date = $_REQUEST['date'];
    //split date into to get month
    $split_date = str_split($date);
    // get only the month
    
    if(count($split_date) == 1){
        $_SESSION['err'] = "Choose A Date!";
    }
    else{
        $month = $split_date[5].$split_date[6];
        // select benefit which the date is entered by the client
        $select_benefit = mysqli_query($conn,"SELECT net_benefit FROM benefit WHERE month(date) = $month");
        if(mysqli_num_rows($select_benefit) > 0){
            while ($benefit_record = mysqli_fetch_assoc($select_benefit)){
                $benefit[count($benefit)] = $benefit_record['net_benefit'];
            }
        }

    }
    

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Report</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
</head>
<body class="w3-container">
    <div class="w3-row w3-light-green">
        <div class="w3-quarter">
            <a href="home.php" class="w3-mobile w3-button w3-margin-left w3-section w3-blue w3-round" style="text-decoration:none;"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
        </div>
        <div class="w3-half">
            <h1 class="w3-container w3-center w3-padding">            
                <b>MONTHLY REPORT<b>
            </h1>
        </div>
        <div class="w3-half"></div>
    </div>
    
    <div class="w3-content w3-pale-green" style="width:80%; height:530px; overflow:auto;">
        <form id="form" class="w3-display-container" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <div class="w3-half w3-padding">
                <input class="w3-input w3-border w3-margin-left"
                type="date" onkeypress=" sendToServer(event);" 
                name="date">
            </div>    
            <div class="w3-half w3-padding">
                <input type="submit" name="submit" value="Show Report"
                class="w3-button w3-opacity w3-round w3-blue w3-margin-left">
            </div> 
        </form>
        
        <table class="w3-table-all w3-margin-bottom">
            <tr class="">
                <th class="">Total Sales Income</th>
                <th class="">Lended To Customer</th>
                <th class="">Lost due to Expiration</th>
                <th class="">Net Benefit</th>
            </tr>


            <?php
            if (isset($month)){
                $select_total_sales = mysqli_query($conn,"SELECT SUM(sale_amount * sales_price) AS total_sales
                                    FROM sales WHERE month(sales_date) = $month");
                if(mysqli_num_rows($select_total_sales) > 0){
                    while ($total = mysqli_fetch_array($select_total_sales)){
                        $total_sales = $total[0] | 0;
                    }
                }

                // select data from expire_medicine table
                $select_expire_money = mysqli_query($conn,"SELECT SUM(amount*whole_sale_price) AS expire_money FROM expire_medicine WHERE month(date) = $month");
                if(mysqli_num_rows($select_expire_money) > 0){
                    while ($data = mysqli_fetch_array($select_expire_money)){
                        $expire_money = $data[0] | 0;
                    }
                }

                // select debt money
                $select_loan = mysqli_query($conn,"SELECT SUM(debt_amount) FROM debt_detail WHERE month(debt_date) = $month");
                if(mysqli_num_rows($select_loan) > 0){
                    while ($loan = mysqli_fetch_array($select_loan)){
                        $loan_money = $loan[0] | 0;
                    }
                }
            ?>
            
            <?php
                if(!is_null($benefit)){
                    $benefit_result = 0;
                    ?>
                    <tr class="">
                    <?php
                    for ($i = 0; $i < count($benefit); $i++){
                        $benefit_result += $benefit[$i];
                    }

                    ?>
                    <td><?php echo $total_sales; ?></td>
                    <td><?php echo $loan_money; ?></td>
                    <td><?php echo $expire_money; ?></td>
                    <td><?php echo $benefit_result - $loan_money - $expire_money; ?></td>
                    </tr>
                    <?php
                }
            }
                
            ?>
        </table>
        
        <h2 class="w3-center">Short Term Medicine Will Display Here</h2>
        <div class="w3-content w3-section" style="width:60%; height:300px;overflow:auto;">
            <table class="w3-table-all w3-margin-bottom w3-text-black">
                <tr class="">
                    <th class="">Generic Name</th>
                    <th class="">commercial Name</th>
                    <th class="">Expire Date</th>
                    <th class="">Amount</th>
                </tr>
                </thead>
                <?php
                    $date = getdate();
                    $year = $date['year'];
                    $mont = $date['mon'];
                    $day = $date['mday'];

                    $curdate=date_create("$year-$mont-$day");
                    date_add($curdate,date_interval_create_from_date_string("60 days"));
                    $two_month_later = date_format($curdate,"Y-m-d");
                    
                    $expired_medicine = mysqli_query($conn, "SELECT 
                    generic_name,comm_name,exp_date,purchase_amount FROM medicine INNER JOIN purchase ON purchase.med_id = medicine.med_id WHERE exp_date <= '$two_month_later'");

                    while ($records = mysqli_fetch_assoc($expired_medicine)) {
                        ?>
                            <tr>
                                <td><?php echo $records['generic_name']; ?></td>
                                <td><?php echo $records['comm_name']; ?></td>
                                <td><?php echo $records['exp_date']; ?></td>
                                <td><?php echo $records['purchase_amount']; ?></td>
                            </tr>
                        <?php 
                    }
                    ?>
                </table>
            </table>    
        </div>
    </div>
    <div class="w3-content w3-center w3-text-red" style="width:80%;">
       <b> 
            <?php
                if(isset($_SESSION['err'])){
                    echo $_SESSION['err'];
                    unset($_SESSION['err']);
                }
            ?>
        </b>
    </div>

</body>
</html>