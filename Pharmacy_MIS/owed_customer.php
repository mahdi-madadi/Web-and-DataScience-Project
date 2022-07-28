<?php

    // prevent opening without login
    if ( session_id() === "" ) { session_start(); }
    if(!(isset($_SESSION['name']) and isset($_SESSION['pass']))){
        header('location:login.php');
    }
    include("dbConnection.php");

// Select records from customer table
$select_query = mysqli_query($conn,"SELECT customer.name,customer.father_name,customer.contact_num,debt_detail.debt_amount,debt_detail.debt_date
                                    FROM debt_detail INNER JOIN customer ON debt_detail.cust_id = customer.cust_id");
        
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Owed Customer</title>
    <link rel="stylesheet" href="w3.css/w3.css">
    <link rel="stylesheet" href="css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <style>
        .overflow{
            overflow:auto;
        }
    </style>
    
</head>
<body class="w3-container">
    <div class="w3-row w3-container w3-light-green w3-round">
        <div class="w3-quarter w3-padding">
            <a href="home.php" class="w3-mobile w3-button w3-margin-left w3-section w3-blue w3-round" style="text-decoration:none;"><i class="fa fa-arrow-alt-circle-left"></i> Back</a>
        </div>
        <div class="w3-half">
            <h1 class="w3-center w3-padding">            
                <b>Add New Medicines</b>
            </h1>
        </div>
        <div class="w3-half"></div>
    </div>
    
    <div class="w3-container" id="alert">
        <h2>
            <?php 
                if(isset($_SESSION['info_msg'])){
                ?>
                <div class="w3-panel w3-green">
                    <?php echo $_SESSION['info_msg'];
                    unset($_SESSION['info_msg']);?>
                </div>
                 <?php   
                } 
            ?>
        </h2>
    </div>
    
    </script>
    <div class="w3-row w3-container">
        <div class="w3-half w3-blue w3-container w3-round-small">
            <div class="w3-panel">
                <h2 class="w3-padding w3-center">New Customer</h2>
            </div>
            <div class="w3-content" style="width:80%;">
                <form action="Owed_customer_controller.php" method="post" onsubmit="return check_input(this);">
                    Name
                    <input id="name" onfocus="clean(this)" type="text" name="customer_name" class="w3-input w3-round w3-border w3-margin-bottom">
                    Father Name
                    <input id="father_name" onfocus="clean(this)" type="text" name="customer_father_name" class="w3-input w3-round w3-border w3-margin-bottom">
                    Phone Number
                    <input id="phone" onfocus="clean(this)" type="text" name="customer_phone" class="w3-input w3-round w3-border w3-margin-bottom">
                    Debt Amount
                    <input id="loan" onfocus="clean(this)" type="text" name="customer_loan_amount" class="w3-input w3-round w3-border w3-margin-bottom">
                    
                    <input type="submit" value="Save" name="submit" class="w3-button w3-mobile w3-round w3-green w3-margin-bottom">
                </form>
            </div>
        </div>
        <div class="w3-half w3-yellow" style="height:480px;overflow:auto;">
            <div class="w3-panel">
                <h2 class="w3-padding w3-center">See Owed Customers</h2>
            </div>
            <div class="w3-content overflow" style="width:90%;">
                <table class="w3-table-all w3-margin-bottom">
                    <tr class="">
                        <th class="">Name</th>
                        <th class="">Father Name</th>
                        <th class="">Phone</th>
                        <th class="">Debt Amount</th>
                        <th>Date</th>
                    </tr>
                    <?php
                        while ($records = mysqli_fetch_assoc($select_query)) {
                            ?>
                                <tr>
                                    <td><?php echo $records['name']; ?></td>
                                    <td><?php echo $records['father_name']; ?></td>
                                    <td><?php echo $records['contact_num']; ?></td>
                                    <td><?php echo $records['debt_amount']; ?></td>
                                    <td><?php echo $records['debt_date']; ?></td>
                                </tr>
                            <?php 
                        }
                    ?>
                </table>
                
            </div>

        </div>

    </div>
    
<script>
    function check_input(e){
        let name = document.getElementById('name').value;
        let father_name = document.getElementById('father_name').value;
        let phone = document.getElementById('phone').value; 
        let loan = document.getElementById('loan').value;
        let noneCharacter = /\W/g;
        let digits = /[0-9]/g;   

        if (name == ""){
            document.getElementById('name').value = "Requird Field can't be empty!";
            return false;
        }
        else if(father_name == ""){
            document.getElementById('father_name').value = "Required Field can't be empty!";
            return false;
        }
        else if(phone == ""){
            document.getElementById('phone').value = "Required Field can't be empty!";
            return false;
        }
        else if(loan == ""){
            document.getElementById('loan').value = "Required Field can't be empty!";
            return false;
        }
        
        else if(!(digits.test(phone))){
            document.getElementById('phone').value = "Only digits are allowed!";
            return false;
        }
        else if(!(digits.test(loan))){
            document.getElementById('loan').value = "Only digits are allowed!";
            return false;
        }
    
        else{
            return true;
        }
    }

    function clean(e){
        e.value = "";        
    }

</script>
</body>
</html>