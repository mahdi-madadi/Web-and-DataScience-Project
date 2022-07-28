<?php
if ( session_id() === "" ) { session_start(); }
date_default_timezone_set("Asia/Kabul");
include("dbConnection.php");
if(isset($_POST['submit'])){
    $customer_name = $_POST['customer_name'];
    $customer_father_name = $_POST['customer_father_name'];
    $customer_contact_num = $_POST['customer_phone'];
    $debt_date = date('Y-m-d');
    $debt_amount = $_POST['customer_loan_amount'];

    $insert_query_customer = "INSERT INTO customer VALUES (NULL,'$customer_name','$customer_father_name','$customer_contact_num');";
    mysqli_query($conn,$insert_query_customer);

    // select for cust_id
    $select_query = mysqli_query($conn,"SELECT cust_id from customer ORDER BY cust_id DESC LIMIT 1");
    $cust_id = mysqli_fetch_assoc($select_query)['cust_id'];

    $insert_query_debt_detail = "INSERT INTO debt_detail VALUES (NULL,$cust_id,'$debt_date',$debt_amount);";
    mysqli_query($conn,$insert_query_debt_detail);
    $_SESSION['info_msg'] = "Data Successfuly added to Database.";
    header('location:owed_customer.php');
}
else{
    $_SESSION['info_msg'] = "Query Failed!";
    header('location:owed_customer.php');
}

?>