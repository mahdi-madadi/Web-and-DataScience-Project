<?php 
// prevent opening without login
if ( session_id() === "" ) { session_start(); }
if(!(isset($_SESSION['name']) and isset($_SESSION['pass']))){
    header('location:login.php');
}
//set default time zone to Kabul
date_default_timezone_set("Asia/Kabul");
//include database connection
include("dbConnection.php");

// Select catagory
$catagory_select = mysqli_query($conn,"SELECT catagory_name FROM medicine_catagory;");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Medicine</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
</head>
<body class="w3-container w3-pale-yellow">
        <div class="w3-content w3-margin-top" style="width:70%;"> 
            <div class="w3-row w3-light-green">
                <div class="w3-quarter w3-padding">
                    <a href="home.php" class="w3-mobile w3-button w3-margin-left w3-section w3-blue w3-round" style="text-decoration:none;"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
                </div>
                <div class="w3-half">
                    <h1 class="w3-container w3-center w3-padding">            
                        <b>Add New Medicines</b>
                    </h1>
                </div>
                <div class="w3-half"></div>
            </div>

        <h3 class="w3-card-1 w3-text-green w3-round">
                <?php
                    
					if (isset($_SESSION['success_msg'])) {
                        echo $_SESSION['success_msg'];
                        unset($_SESSION['success_msg']);
					}
				?>
            </h3>
        <div class="w3-card-4 w3-content w3-margin-top">
            <form id="f" action="addController.php" method="post" onsubmit="return check_for_insert();">
                <div class="w3-container w3-light-gray">
                    <label for="gen_name" class=" w3-round">Generic Name</label>
                    <input type="text" id="gen_name" name="generic_name" placeholder="Generic Name" class="w3-input w3-round w3-border w3-margin-bottom">
                    <label for="comm_name" class=" w3-round">Commercial Name</label>
                    <input type="text" id="comm_name" name="commercial_name" placeholder="Commercial Name" class="w3-input w3-round w3-border w3-margin-bottom">
                    <label for="exp_date" class=" w3-round">Expire Date</label>
                    <input type="date" id="exp_date" name="expire_date" placeholder="Expire Date" min="2022-01-01" class="w3-input w3-round w3-border w3-margin-bottom">
                    <label for="purch_amount" class=" w3-round">Amount</label>
                    <input type="number" id="purch_amount" name="purchase_amount" placeholder="Amount" class="w3-input w3-border w3-round w3-margin-bottom">
                    <label for="purch_price" class=" w3-round">Purchased Price</label>
                    <input type="text" id="purch_price" name="purchase_price" placeholder="Purched Price" class="w3-input w3-border w3-round w3-margin-bottom">
                    <label for="retail_price" class=" w3-round">Retail Price</label>
                    <input type="text" id="retail_price" name="retail_price" placeholder="Retial Price" class="w3-input w3-border w3-round w3-margin-bottom">
                    <label for="cat" class=" w3-round">Catagory</label>
                    <select id="cat" name="catagory" class="w3-input w3-border w3-round w3-margin-bottom">
                        <?php
                            while ($records = mysqli_fetch_assoc($catagory_select)) {
                                ?>
                                    <option class="w3-text-black"><?php echo $records['catagory_name']; ?></option>
                                <?php 
                            }
                        ?>
                    </select>
                    <input type="submit" name="submit" value="Add to Inventory" class="w3-mobile w3-button w3-black w3-right w3-margin-bottom w3-opacity w3-round-large">
                </div>
            </form>
        </div>

    </div>
        

<script>
    function check_for_insert(){
        let cat = document.getElementById('cat').value;
        let gen_name = document.getElementById('gen_name').value;
        let comm_name = document.getElementById('comm_name').value;
        let exp_date = document.getElementById('exp_date').value;
        let purch_amount = document.getElementById('purch_amount').value;
        let purch_price = document.getElementById('purch_price').value;
        let retail_price = document.getElementById('retail_price').value;
        
        if (gen_name == "" | comm_name == "" | exp_date == "" | purch_amount == "" | purch_price == "" | retail_price == "" | cat == ""){
            let alert_msg = "Required field cann't be empty!";
            alert(alert_msg);
            return false;         
        }
        else {
            return true;
        }
    }

</script>
</body>
</html>
