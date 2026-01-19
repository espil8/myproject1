<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// connect to database
$db = mysqli_connect('localhost', 'root', '', 'm_users');


$am = "";
$email    = "";
$errors   = array(); 
$section_id="";

// call the register() function ONLY if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register_btn'])) {
    register();
}





function registerLab() {
    global $db, $errors;

    $am = e($_POST['am']);
    $section_id = isset($_POST['section_id']) ? e($_POST['section_id']) : '';

    // Validation
    if (empty($section_id)) {
        array_push($errors, "Πρέπει να επιλέξεις εργαστήριο!");
    }

    // Έλεγχος διπλής εγγραφής
    if (!empty($section_id)) {
        $check = mysqli_query($db, "SELECT * FROM registrations WHERE am='$am' AND section_id='$section_id'");
        if (mysqli_num_rows($check) > 0) {
            array_push($errors, "Έχετε ήδη εγγραφεί σε αυτό το εργαστήριο!");
        }
    }

    // Έλεγχος για το αν ο φοιτητής μπορεί να εγγραφεί στο εργαστήριο βάσει μέγιστου αριθμού φοιτητών 
    if (!empty($section_id)) {
        $countQuery = mysqli_query($db, "SELECT COUNT(*) AS total FROM registrations WHERE section_id='$section_id'");
        $countRow = mysqli_fetch_assoc($countQuery);
        $current_students = (int)$countRow['total'];

        $maxQuery = mysqli_query($db, "SELECT max_students FROM sections WHERE id='$section_id' LIMIT 1");
        $lab = mysqli_fetch_assoc($maxQuery);
        $max_students = (int)$lab['max_students'];

        if ($current_students >= $max_students) {
            array_push($errors, "❌ Το εργαστήριο είναι πλήρες ({$max_students} άτομα).");
        }
    }

    // Αν υπάρχουν errors τότε redirect πίσω με session
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        header('Location: register_lab.php');
        exit();
    }

    // Αν όλα είναι σωστά -> insert
    $insert = mysqli_query($db, "INSERT INTO registrations (am, section_id) VALUES ('$am', '$section_id')");
    if (!$insert) {
        die("SQL INSERT ERROR: " . mysqli_error($db));
    }

    $_SESSION['success'] = "Η εγγραφή στο εργαστήριο ολοκληρώθηκε!";
    header('Location: dashboard.php');
    exit();
}



function unregisterLab() {
    global $db;
    if (isset($_POST['lab_id']) && isset($_SESSION['user'])) {
        $am = $_SESSION['user']['am'];
        $lab_id = $_POST['lab_id'];

        $stmt = $db->prepare("DELETE FROM registrations WHERE am = ? AND section_id = ?");
        $stmt->bind_param("si", $am, $lab_id);
        $stmt->execute();
        $stmt->close();
    }
}

function register(){
    global $db, $errors, $am, $email;

    $password_1 = isset($_POST['password']) ? e($_POST['password']) : '';
    $password_2 = isset($_POST['password_2']) ? e($_POST['password_2']) : '';
    $am = isset($_POST['am']) ? e($_POST['am']) : '';
    $email = isset($_POST['email']) ? e($_POST['email']) : '';

    // Validation
    if (empty($am)) { array_push($errors, "Είναι απαραίτητος ο Αριθμός Μητρώου"); }
    if (empty($email)) { array_push($errors, "Email is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) { array_push($errors, "Οι δύο κωδικοί δεν ταιριάζουν"); }

    // Έλεγχος αν υπάρχει ήδη ο ΑΜ
    if (!empty($am)) {
        $check_query = "SELECT * FROM users WHERE am='$am' LIMIT 1";
        $result = mysqli_query($db, $check_query);
        if(mysqli_num_rows($result) > 0){
            array_push($errors, "Αυτός ο Αριθμός Μητρώου υπάρχει ήδη");
        }
    }

    // Έλεγχος αν ο κωδικός χρησιμοποιείται ήδη
    if (!empty($password_1)) {
        $query_passwords = "SELECT password FROM users";
        $result_passwords = mysqli_query($db, $query_passwords);
        while($row = mysqli_fetch_assoc($result_passwords)) {
            if(password_verify($password_1, $row['password'])){
                array_push($errors, "Ο κωδικός που επέλεξες χρησιμοποιείται ήδη. Διάλεξε έναν άλλο.");
                break;
            }
        }
    }

    // Αν υπάρχουν errors --> redirect πίσω με τα μηνύματα
    if (count($errors) > 0) {
        $_SESSION['errors'] = $errors;
        $_SESSION['am'] = $am;
        $_SESSION['email'] = $email;
        header('Location: register.php');
        exit();
    }

    // Αν όλα είναι σωστά, καταχώρηση χρήστη
    $password_hashed = password_hash($password_1, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (am, email, user_type, password) 
              VALUES('$am', '$email', 'user', '$password_hashed')";
    mysqli_query($db, $query);

    $_SESSION['success'] = "Η εγγραφή ολοκληρώθηκε!";
    header('Location: login.php');
    exit();
}

function getAllLabs()
{
        global $db;
		$query = "SELECT id,day,time,max_students from sections";    
		$result=mysqli_query($db, $query);
		
		if($result->num_rows > 0)
		{
			echo "Λίστα Διαθέσιμων Εργαστηρίων:<br>";
			while($row=$result->fetch_assoc())
			{
				echo "Κωδ.Εργαστηρίου:".$row["id"]." Ημέρα:".$row["day"]." Ώρα:".$row["time"]." Μέγιστο πλήθος φοιτητών:".$row["max_students"]."<br><br>";
			}
		
		}
		else
			echo "empty list of Labs<br>";


}








function getAllSections() {
    global $db;
    $query = "SELECT id, name, day, time, max_students FROM sections ORDER BY id ASC";
    return mysqli_query($db, $query);
}


// Επιστρέφει τα στοιχεία ενός εργαστηρίου με βάση το ID
function getLabById($id) {
    global $db; 

    $id = (int)$id; // μετατροπή σε ακέραιο
    $result = mysqli_query($db, "SELECT * FROM sections WHERE id = $id LIMIT 1");

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return false; // αν δεν βρεθεί
}


// συνάρτηση για να παίρνουμε χρήστη με βάση το AM
function getUserByAm($am){
    global $db;
    $am = mysqli_real_escape_string($db, $am);
    $query = "SELECT * FROM users WHERE am='$am' LIMIT 1";
    $result = mysqli_query($db, $query);
    if($result && mysqli_num_rows($result) > 0){
        return mysqli_fetch_assoc($result);
    }
    return null;
}

// escape string
function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
    if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) {
        echo '<div class="error">';
        foreach ($_SESSION['errors'] as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
        // Καθαρίζουμε τα errors μετά την εμφάνιση
        unset($_SESSION['errors']);
    }
}

//μήνυμα για επιτυχή εγγραφή χρήστη
function display_success() {
    if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
        echo '<div class="success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']); // Καθαρίζει το μήνυμα μετά την εμφάνιση
    }
}


function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}


function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login() {
    global $db, $am, $errors;

    $am = e($_POST['am']);
    $password = e($_POST['password']);

    // Validation
    if (empty($am)) { array_push($errors, "Ο ΑΜ είναι υποχρεωτικός"); }
    if (empty($password)) { array_push($errors, "Ο Κωδικός πρόσβασης είναι υποχρεωτικός"); }

    if (count($errors) == 0) {
        $query = "SELECT * FROM users WHERE am='$am' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) {
            $user = mysqli_fetch_assoc($results);

            // Έλεγχος κωδικού με password_verify
            if (password_verify($password, $user['password'])) {
                // επιτυχής login
                $_SESSION['user'] = $user;
                $_SESSION['success'] = "You are now logged in";

                if ($user['user_type'] == 'admin') {
                    header('location: admin/home.php');
                } else {
                    header('location: dashboard.php');
                }
                exit();
            } else {
                array_push($errors, "Λάθος συνδυασμός ΑΜ/Κωδικού");
            }
        } else {
            array_push($errors, "Ο χρήστης δεν βρέθηκε");
        }
    }


    if (count($errors) > 0) {
    $_SESSION['errors'] = $errors;
    header('Location: login.php');
    exit();
                           }


    
}





// =======================
// ADMIN - ADD LAB
// =======================
function addLab() {
    global $db, $errors;

    $day = e($_POST['day']);
    $time = e($_POST['time']);
    $max = e($_POST['max_students']);
    $name = e($_POST['name']);

    if (empty($day) || empty($time) || empty($max)) {
        array_push($errors, "Όλα τα πεδία είναι υποχρεωτικά");
        return;
    }

    $query = "INSERT INTO sections (day, time, max_students, name)
              VALUES ('$day', '$time', '$max', '$name')";
    mysqli_query($db, $query);

    $_SESSION['success'] = "✅ Το εργαστήριο προστέθηκε!";
}

// =======================
// ADMIN - DELETE LAB (id > 3)
// =======================
function deleteLab($id) {
    global $db;

    if ($id <= 3) {
        $_SESSION['success'] = "❌ Τα default εργαστήρια δεν διαγράφονται";
        return;
    }

    // Πρώτα registrations
    mysqli_query($db, "DELETE FROM registrations WHERE section_id=$id");
    // Μετά section
    mysqli_query($db, "DELETE FROM sections WHERE id=$id");

    $_SESSION['success'] = "🗑 Το εργαστήριο διαγράφηκε";
} 




// ================= LAB FOLDERS =================

// Όλοι οι φάκελοι ενός εργαστηρίου
function getLabFolders($section_id) {
    global $db;
    $section_id = (int)$section_id;
    return mysqli_query(
        $db,
        "SELECT * FROM lab_folders WHERE section_id = $section_id ORDER BY created_at ASC"
    );
}

// Δημιουργία φακέλου (admin)
function addLabFolder($section_id, $title) {
    global $db;
    $section_id = (int)$section_id;
    $title = mysqli_real_escape_string($db, $title);

    return mysqli_query(
        $db,
        "INSERT INTO lab_folders (section_id, title)
         VALUES ($section_id, '$title')"
    );
}


// Διαγραφή φακέλου (admin)
function deleteLabFolder($folder_id) {
    global $db;
    $folder_id = (int)$folder_id;

    return mysqli_query(
        $db,
        "DELETE FROM lab_folders WHERE id = $folder_id"
    );
}



// ================= LAB FILES =================

// Αρχεία ενός φακέλου
function getFolderFiles($folder_id) {
    global $db;
    $folder_id = (int)$folder_id;

    return mysqli_query(
        $db,
        "SELECT * FROM lab_files WHERE folder_id = $folder_id ORDER BY uploaded_at ASC"
    );
}

// Upload PDF (admin)
function uploadLabFile($folder_id, $file) {
    global $db;

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Σφάλμα στο upload";
    }

    // ΜΟΝΟ PDF
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'pdf') {
        return "Επιτρέπονται μόνο PDF αρχεία";
    }

    $folder_id = (int)$folder_id;
    $original_name = mysqli_real_escape_string($db, $file['name']);

    $new_name = uniqid('pdf_', true) . '.pdf';
    $target_path = 'uploads/labs/' . $new_name;

    if (!move_uploaded_file($file['tmp_name'], $target_path)) {
        return "Αποτυχία αποθήκευσης αρχείου";
    }

    mysqli_query(
        $db,
        "INSERT INTO lab_files (folder_id, filename, original_name)
         VALUES ($folder_id, '$new_name', '$original_name')"
    );

    return true;
}



// Διαγραφή PDF (admin)
function deleteLabFile($file_id) {
    global $db;

    $file_id = (int)$file_id;

    // Πάρε το filename
    $result = mysqli_query(
        $db,
        "SELECT filename FROM lab_files WHERE id = $file_id LIMIT 1"
    );

    if ($row = mysqli_fetch_assoc($result)) {
        $file_path = 'uploads/labs/' . $row['filename'];

        // Διαγραφή αρχείου
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Διαγραφή από βάση
        mysqli_query(
            $db,
            "DELETE FROM lab_files WHERE id = $file_id"
        );
    }
}