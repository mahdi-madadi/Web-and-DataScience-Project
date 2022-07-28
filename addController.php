<?php
include("dbConnection.php");

if(isset($_POST['submit'])){
    // get the user input values
    $gen_name = $_REQUEST['generic_name'];
    $comm_name = $_REQUEST['commercial_name'];
    $exp_date = $_REQUEST['expire_date'];
    $purch_date = date('Y-m-d');
    $purch_amount = $_REQUEST['purchase_amount'];
    $purch_price = $_REQUEST['purchase_price'];
    $retail_price = $_REQUEST['retail_price'];
    $catagory_name = $_REQUEST['catagory'];
    
    global $conn;
    // select for catagory id
    $select_catagory_id = mysqli_query($conn,"SELECT catagory_id from medicine_catagory where catagory_name = '$catagory_name';");
    
    $catagory_id = mysqli_fetch_assoc($select_catagory_id)['catagory_id'];

    $insert_query_medicine = "INSERT INTO medicine VALUES (NULL,'$gen_name','$comm_name','$exp_date',$catagory_id,$retail_price);";
    mysqli_query($conn,$insert_query_medicine);

    // select for med_id
    $select_query = mysqli_query($conn,"SELECT med_id from medicine ORDER BY med_id DESC LIMIT 1");
    $med_id = mysqli_fetch_assoc($select_query)['med_id'];
    
    $insert_query = "INSERT INTO purchase VALUES (NULL,$med_id,'$purch_date',$purch_amount,$purch_price);";
    if (mysqli_query($conn, $insert_query)) {
        session_start();
        $_SESSION['success_msg'] = "Successfully Added!";
        header('location:addNewMedicine.php');        
    } else {
        session_start();
        $_SESSION['success_msg'] =  "Error: "  . "<br>" . mysqli_error($conn);
        header('location:addNewMedicine.php');
    }
    
}

mysqli_close($conn);
?>