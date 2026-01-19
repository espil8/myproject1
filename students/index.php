<?php
include('functions.php');

// Έλεγχος αν έχει γίνει login
if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
    exit();
}

// Αν είναι admin, τον στέλνουμε στη σελίδα του admin
if (isAdmin()) {
    header('location: admin/home.php');
    exit();
}

// Αν είναι φοιτητής
header('location: dashboard.php');
exit();

