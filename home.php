<?php 
// prevent opening without login
if ( session_id() === "" ) { session_start(); }
if (!(isset($_SESSION['name']) and isset($_SESSION['pass']))){
    header('location:login.php');
}
//set default time zone to Kabul
date_default_timezone_set("Asia/Kabul");

//include database connection
include("dbConnection.php");
//check if you are loged in

//select med_id where the purchase amount is zero
$med_id_select = mysqli_query($conn,"SELECT med_id FROM purchase WHERE purchase_amount = 0;");
while ($med_id = mysqli_fetch_assoc($med_id_select)){
    $row = $med_id['med_id'];
    //delete the empty row 
    mysqli_query($conn,"DELETE FROM medicine WHERE med_id = $row");
}


//select query
$select_query = mysqli_query($conn,"SELECT medicine.generic_name,medicine.comm_name,medicine.exp_date,
                purchase.purchase_amount,medicine.retail_price,medicine_catagory.catagory_name
                FROM ((purchase
                INNER JOIN medicine ON purchase.med_id = medicine.med_id)
                INNER JOIN medicine_catagory ON medicine.catagory_id = medicine_catagory.catagory_id);");


// count the number of medicines which will become expire 2 months later

// get current date
$date = getdate();
$year = $date['year'];
$mont = $date['mon'];
$day = $date['mday'];
// create current date with date_create() func
$curdate=date_create("$year-$mont-$day");
// add two months to the current date
date_add($curdate,date_interval_create_from_date_string("60 days"));
$two_month_later = date_format($curdate,"Y-m-d");
// select and count the short date medicine
$select_number = mysqli_query($conn, "SELECT 
COUNT(exp_date) AS c FROM medicine WHERE exp_date <= '$two_month_later'");
if (mysqli_num_rows($select_number) > 0){
    // number of short date medicine
    $number_of_medicine = mysqli_fetch_assoc($select_number)['c'];
}
else{
    $number_of_medicine = "";
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .overflow{
            overflow:auto;
        }
    </style>
</head>

<body class="w3-container-fluide" style="background-image:url('image/suspinision.jpg');">

    <!-- Sidebar -->
    <div class="w3-sidebar w3-bar-block w3-border-right" style="display:none" id="mySidebar">
        <button onclick="w3_close()" class="w3-bar-item w3-large w3-dark-gray">Close &times;</button>
        <a href="home.php" class="w3-bar-item w3-green w3-button"><i class="fa fa-home"></i></a>
        <a href="addNewMedicine.php" class="w3-bar-item w3-button">ADD NEW MEDICINE</a>
        <a href="sellMedicine.php" class="w3-bar-item w3-button">SELL MEDICINE</a>
        <a href="createReport.php" class="w3-bar-item w3-button">REPORT <span class="w3-badge w3-red"><?php echo $number_of_medicine; ?></span></a>
        <a href="owed_customer.php" class="w3-bar-item w3-button">OWED CUSTOMER</a>
        <a href="developers.php" class="w3-bar-item w3-button">DEVELOPERS</a>
        <a href="login.php" class="w3-bar-item w3-button">LOGIN</a>

    </div>
    
    <!-- Header -->
    <div id="header" class="w3-light-gray w3-row" style="width:100%;">
        <div class="w3-quarter w3-section w3-mobile">
            <input id="search" type="search" placeholder="Search medicine" onkeyup="searchMedicine()"
            class="w3-input w3-border w3-round-large w3-margin-left" style="width:94%"/>
        </div>
        <div id="title" class="w3-half w3-padding w3-mobile">
            <h3 class="w3-opacity w3-center"><b>Mohammad Arif Pharmacy</b></h3>
        </div>
        <div class="w3-mobile w3-quarter w3-center w3-section">
            <div class="w3-container">
                <?php
                    echo date("l,M d,Y") . "<br>";
                ?>
            </div>
            <div class="w3-container" id="time">
            </div>
        </div>
    </div>
    <div class="w3-bar w3-sand w3-topbar w3-border-dark-blue">
        <a href="home.php" class="w3-bar-item w3-button w3-green w3-hide-small w3-hide-medium"><i class="fa fa-home"></i></a>
        <a href="addNewMedicine.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">ADD NEW MEDICINE</a>
        <a href="sellMedicine.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">SELL MEDICINE</a>
        <a href="createReport.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">REPORT <span class="w3-badge w3-red"><?php echo $number_of_medicine; ?></span></a>
        <a href="owed_customer.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">OWED CUSTOMER</a>
        <a href="developers.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">DEVELOPERS</a>
        <a href="login.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium">LOGIN</a>
        <a href="#" class="w3-bar-item w3-button w3-left
            w3-hide-large" onclick="w3_open()">
            <i class="fa-solid fa-align-justify w3-xlarge"></i>
        </a>
    </div>
    <!-- content of the page -->
    <div class="w3-container w3-content w3-mobile w3-margin-top" style="width:80%;height:480px; overflow:auto;">
        <table class="w3-table-all overflow" id='myTable'>
            <thead>
                <tr>
                    <th class="w3-light-grey" style="position:sticky;top:0;">ID</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Gen Name</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Com Name</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Expire Date</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Price</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Amount</th>
                    <th class="w3-light-grey" style="position:sticky;top:0;">Catagory</th>
                </tr>
            </thead>
            <?php
                $i = 0;
                while ($records = mysqli_fetch_assoc($select_query)) {
                    $i++;
                    ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $records['generic_name']; ?></td>
                            <td><?php echo $records['comm_name']; ?></td>
                            <td><?php echo $records['exp_date']; ?></td>
                            <td><?php echo $records['retail_price']; ?></td>
                            <td><?php echo $records['purchase_amount']; ?></td>
                            <td><?php echo $records['catagory_name']; ?></td>
                        </tr>
                    <?php 
                }
                ?>
        </table>
    </div>
    <div class="w3-dark-gray w3-center w3-margin-top w3-display-bottom" style="height:100px;">
        <footer>
        <div class="footer-content">
            <h3>Student Project</h3>
            <p>This ofline website is developed by Kabul University students for a local pharmacy to have more control over their business.</p>
            <ul class="socials">
                <li><a href="https://www.facebook.com/Mahdi Madadi"><i class="fa-brands fa-facebook"></i></a></li>
                <li><a href="https://www.teitter.come/Mujtab123"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="https://www.google.com"><i class="fa-brands fa-google-plus"></i></a></li>
                <li><a href="https://www.youtube.com/mahdiAndmujtabaChanel22"><i class="fa-brands fa-youtube"></i></a></li>
            </ul>
        </div>
        <div class="footer-bottom">
            <p> &copy;<?php echo date("Y");?>. Developed by <span>Mahdi and Mujtaba</span></p>
        </div>
    </footer>

    </div>
    
<script>
    function w3_open() {
        document.getElementById("mySidebar").style.display = "block";
    }

    function w3_close() {
        document.getElementById("mySidebar").style.display = "none";
    }

    // set time and update it after one second
    function showTime(){
        let time = new Date();
        document.getElementById('time').innerText = time.getHours() + ":" + time.getMinutes() + ":" + time.getSeconds();
    }
    setInterval(showTime,1000);

    // search medicines in table
    function searchMedicine() {
        let input, filter, table, tr, td, i;
        input = document.getElementById("search");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                let txtValue = td.textContent || td.innerText || td.value;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                tr[i].style.display = "none";
                }
            }
        }
    }
</script>
</body>
</html>