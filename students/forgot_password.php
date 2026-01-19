<?php
include('functions.php');

if (isset($_POST['check_user'])) {
    $am = e($_POST['am']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    if (empty($_POST['am'])) {
        $errors[] = "Ο ΑΜ είναι υποχρεωτικός";
    }

    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header("Location: forgot_password.php");
        exit();
    }

    // αν είναι ΟΚ προχώρα (redirect στο reset)
}


        $user = getUserByAm($am);

        if (!$user) {
            $_SESSION['errors'][] = "Ο χρήστης δεν βρέθηκε";
        } else {
            $_SESSION['reset_am'] = $am;
            header("Location: reset_password.php");
            exit();
        }
    }


?>

<!DOCTYPE html>
<html>
<head>
    <title>Επαναφορά Κωδικού</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">
    <h2>Επαναφορά Κωδικού</h2>
</div>

<form method="post">
    <?php echo display_error(); ?>

    <div class="input-group">
        <label>Αριθμός Μητρώου</label>
        <input type="text" name="am">
    </div>

    <div class="input-group">
        <button type="submit" class="btn" name="check_user">
            Συνέχεια
        </button>


    <div style="margin-top:10px;">
       <a href="login.php" class="btn" style="background:#777;">
           ⬅ Επιστροφή στην Είσοδο
        </a>
    </div>

    </div>
</form>

</body>
</html>
