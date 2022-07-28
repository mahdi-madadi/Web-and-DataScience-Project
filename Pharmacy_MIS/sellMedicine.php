<?php
    //set default time zone to Kabul
    date_default_timezone_set("Asia/Kabul");
    // prevent opening without login
    if ( session_id() === "" ) { session_start(); }
    if(!(isset($_SESSION['name']) and isset($_SESSION['pass']))){
        header('location:login.php');
    }
    //include database connection
    include("dbConnection.php");

    // Select catagory, generic name, commercial name
        $catagory_select = mysqli_query($conn,"SELECT catagory_name FROM medicine_catagory;");
        
        $generic_name_select = mysqli_query($conn,"SELECT generic_name FROM medicine;");
        $commercial_name_select = mysqli_query($conn,"SELECT comm_name FROM medicine;");
        

    if(isset($_POST['submit'])){
    
        // variables/ column names
        $gen_name = $_POST['medicine'];
        $catagory_name = $_POST['catagory'];
        $commercial_name = $_POST['commercial'];
        $sales_date = date('Y-m-d');
        $sales_amount = $_POST['amount'];
        $price = $_POST['price'];
 
        $catagory_id_select = mysqli_query($conn,"SELECT catagory_id FROM medicine_catagory WHERE
         catagory_name = '$catagory_name';");
        $catagory_id = mysqli_fetch_assoc($catagory_id_select)['catagory_id'];
        $med_id_select = mysqli_query($conn,"SELECT med_id FROM medicine WHERE generic_name = '$gen_name' AND comm_name = '$commercial_name' AND catagory_id = $catagory_id;");
        $med_id = mysqli_fetch_assoc($med_id_select)['med_id'];
        
        //select amount of medicines you have in your stock
        $purchase_amount_select = mysqli_query($conn,"SELECT purchase_amount FROM purchase WHERE med_id = $med_id;");
        $purchase_amount = mysqli_fetch_assoc($purchase_amount_select)['purchase_amount'];

        //select purchase price 
        $purchase_price_select = mysqli_query($conn,"SELECT purchase_price FROM purchase WHERE med_id = $med_id");
        $purchase_price = mysqli_fetch_assoc($purchase_price_select)['purchase_price'];

        //net benefit
        $benefit = ($price - $purchase_price)*$sales_amount;
        //check if you have enough medicines to sell
        if($purchase_amount >= $sales_amount){
            $insert_sales_query = "INSERT INTO sales VALUES (NULL,$med_id,'$sales_date',$sales_amount,$catagory_id,$price);";
            mysqli_query($conn,$insert_sales_query);

            // insert into benefit 
            mysqli_query($conn,"INSERT INTO benefit VALUES (NULL,$benefit,'$sales_date');");

            $_SESSION['alert_msg'] = "You sold medicines from your stock";
        }
        else{
            $_SESSION['alert_msg'] = "You do not have enough medicine to sell!";
        }
        
    }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Save Sales Records</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
</head>
<body class="w3-container w3-blue-grey">
    <div class="w3-content" style="width:70%;">
        <div class="w3-row w3-light-green w3-panel">
            <div class="w3-quarter w3-padding">
                <a href="home.php" class="w3-mobile w3-button w3-margin-left w3-section w3-blue w3-round" style="text-decoration:none;"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
            </div>
            <div class="w3-half">
                <h1 class="w3-container w3-center w3-padding">            
                    <b>Sales Medicine</b>
                </h1>
            </div>
            <div class="w3-half"></div>
        </div>
        <h3 class="w3-card-1 w3-text-green w3-round">
            <?php
                
                if (isset($_SESSION['alert_msg'])) {
                    echo $_SESSION['alert_msg'];
                    unset($_SESSION['alert_msg']);
                }
            ?>
        </h3>
        <div class="w3-container-fluid w3-blue" style="height:auto;">
            <form action="sellMedicine.php" id="f" class="w3-container" onsubmit="return control_null();" method="post">
                <br>
                Medicine Name
                <select id="med" name="medicine" class="w3-input w3-border w3-round w3-margin-bottom">
                    <?php
                        while ($records = mysqli_fetch_assoc($generic_name_select)) {
                            ?>
                                <option class="w3-text-black"><?php echo $records['generic_name']; ?></option>
                            <?php 
                        }
                    ?>
                </select>
        
                Commercial Name
                <select id="com" name="commercial" class="w3-input w3-border w3-round w3-margin-bottom">
                    <?php
                        while ($records = mysqli_fetch_assoc($commercial_name_select)) {
                            ?>
                                <option class="w3-text-black"><?php echo $records['comm_name']; ?></option>
                            <?php 
                        }
                    ?>
                </select>
               
                Catagory
                <select id="cat" name="catagory" class="w3-input w3-border w3-round w3-margin-bottom">
                    <?php
                        while ($records = mysqli_fetch_assoc($catagory_select)) {
                            ?>
                                <option class="w3-text-black"><?php echo $records['catagory_name']; ?></option>
                            <?php 
                        }
                    ?>
                </select>
                Amount
                <input type="text" name="amount" id="amount" class="w3-input w3-round w3-border w3-margin-bottom">
                Price
                <input type="text" name="price" id="price" class="w3-input w3-round w3-border w3-margin-bottom">
                <a href="home.php" class="w3-mobile w3-button w3-round w3-purple w3-left">Back Home</a>
                <input type="submit" name="submit" class="w3-mobile w3-button w3-right w3-indigo w3-round w3-mobile" value="Save">
                <br>
                <br>
                Total Price
                <input type="text" id="total" class="w3-input w3-margin-bottom w3-padding-32" readonly>
                <input type="button" class="w3-button w3-margin-bottom w3-black w3-round w3-mobile" value="Calc" onclick="calc_total_price();">
            </form>
        </div>
    </div>
    
    <script>
        
        let total_list = [];
        function calc_total_price(){
            let med = document.getElementById('med').value;
            let amount = document.getElementById('amount').value;
            let price = document.getElementById('price').value;
            let com = document.getElementById('com').value;
            
            let result = amount * price;
            total_list.push(result);
            let sum = total_list.reduce(myFunction);
            document.getElementById("total").value = "Your total payment is: " + sum + " Af";

            function myFunction(total, value) {
                return total + value;
            }
        }

        function control_null(){
            let med = document.getElementById('med').value;
            let amount = document.getElementById('amount').value;
            let price = document.getElementById('price').value;
            let com = document.getElementById('com').value;
            
            if (med == "" | amount == "" | price == "" | com == ""){
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
