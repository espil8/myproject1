<?php 
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
} 


// --- ADD LAB & DELETE LAB --- 
if (isset($_POST['add_lab'])) {
    addLab();
    header("Location: home.php");
    exit();
}

if (isset($_GET['delete_lab'])) {
    deleteLab((int)$_GET['delete_lab']);
    header("Location: home.php");
    exit();
}



// --- UPDATE MAX STUDENTS ---
if (isset($_POST['update_max'])) {
    $section_id = (int)$_POST['section_id'];
    $new_max = (int)$_POST['max_students'];

    if ($new_max < 1) {
        $new_max = 1;
    }

    
    $stmt = mysqli_prepare($db, "UPDATE sections SET max_students = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $new_max, $section_id);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        $_SESSION['success'] = "Ο μέγιστος αριθμός φοιτητών για το εργαστήριο ενημερώθηκε σε $new_max!";
    } else {
        $_SESSION['success'] = "Σφάλμα κατά την ενημέρωση: " . mysqli_error($db);
    }

    mysqli_stmt_close($stmt);

    header("Location: home.php");
    exit();
}




?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="../style.css">
	<style>
	.header {
		background: #003366;
		min-height: 60px;
		
	}
	.content {
    padding-bottom: 90px;
    

             }
	button[name=register_btn] {
		background: #003366;
	}
	.profile_info a {
    display: inline-block;
    padding: 6px 12px;
    margin-top: 6px;
    background: #003366;
    color: white !important;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.25s ease;
}

.profile_info a:hover {
    background: #005580;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.25);
}
/* ΚΟΥΜΠΙ ΑΠΟΣΥΝΔΕΣΗΣ */
.profile_info a[href*="logout"] {
    background: #a94442;
}

.profile_info a[href*="logout"]:hover {
    background: #c9302c;
}

/* ΚΟΥΜΠΙ ΠΡΟΣΘΗΚΗΣ ΧΡΗΣΤΗ */
.profile_info a[href*="create_user.php"] {
    background: #003366;
}

.profile_info a[href*="create_user.php"]:hover {
    background: #005580;
}

.profile_info img {
    width: 72px;
    height: 72px;
}
	
body {
    background: linear-gradient(-45deg, #9a9ee2ff, #9f98beff, #767aacff, #c4c1beff);
    background-size: 400% 400%;
    animation: flame 8.5s ease infinite;
    height: 100vh;
    margin: 0;
}

@keyframes flame {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}



.fade-message {
    color: #3a546eff;           /* admin χρώμα */
    font-weight: bold;
    font-size: 18px;
    text-align: center;
    margin-bottom: 15px;
    animation: subtleFade 6s infinite; /* πιο αργό και ήπιο */
}

@keyframes subtleFade {
    0%, 100% { opacity: 2; }
    50% { opacity: 0.8; }  /* πολύ μικρή αλλαγή */
}
  



	</style>
</head>
<body>
	<div class="header">
		<h2>Αρχική Σελίδα Διαχειριστή</h2>
	</div>
	<div class="content">
		 <p class="fade-message">Διαχείριση Εργαστηρίων</p>
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<div class="profile_info">
			<img src="../admin_icon.png"  >

			<div>
				<?php if (isset($_SESSION['user'])) { ?> 
					<strong><?php echo $_SESSION['user']['am']; ?></strong>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<a href="home.php?logout='1'" style="color: red;">Αποσύνδεση</a>
                       &nbsp; <a href="create_user.php"> + προσθήκη χρήστη</a>	
                       <br>
                       <br>
                       <a href="view_users.php">📋 προβολή χρηστών</a> 
                        					
					</small>

<div id="admin-timestamp" style="text-align:center; margin:0 20px 0; color:#003366; font-weight:bold;"></div>

<script>
function updateTimestamp() {
    const now = new Date();
    const options = { 
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', 
        hour: '2-digit', minute: '2-digit', second: '2-digit' 
    };
    document.getElementById('admin-timestamp').innerText = now.toLocaleDateString('el-GR', options);
}

updateTimestamp();
setInterval(updateTimestamp, 1000);
</script>

	       <div class="labs-box" style="border: 2px solid #B0C4DE; border-radius:5px; padding:12px; background:#f9f9f9;">
             <p style="font-weight: bold; margin-bottom: 10px;">Διαθέσιμα Εργαστήρια</p>

               <table style="width:100%; border-collapse: collapse; margin:0; padding:0;">

                 <thead>
                   <tr>
                     <th style="width:120px; text-align:left; padding:6px;">Εργαστήριο</th>
                     <th style="width:150px; text-align:left; padding:6px;">Ημέρα</th>
                     <th style="width:80px; text-align:left; padding:6px;">Ώρα</th>
                     <th style="width:140px;">Μέγιστοι</th>
                     <th style="width:80px; text-align:left; padding:6px;">Ενέργεια</th>
                    </tr>
                 </thead>

           <tbody>

            <?php
              $result = getAllSections();
            ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                  <td style="padding:6px;">
    <a href="../lab.php?id=<?= $row['id'] ?>">
        <?= isset($row['name']) ? htmlspecialchars($row['name']) : '—' ?>
    </a>
           </td>
                          <td style="padding:6px;"><?php echo $row['day']; ?></td>
                          <td style="padding:6px;"><?php echo $row['time']; ?></td>

                          <td style="padding:6px; vertical-align: middle; text-align:center;">
                          <?php if ($row['id'] > 3): ?>
                           <form method="post" style="display:inline-flex; gap:4px; align-items:center; margin:0;">
                                <input type="hidden" name="section_id" value="<?= $row['id'] ?>">
                                <input type="number"
                                 name="max_students"
                                 value="<?= $row['max_students'] ?>"
                                 min="1"
                                style="width:45px; height:25px; text-align:center; padding:2px;">
                                <button type="submit"
                                  name="update_max"
                                style="padding:2px 6px; height:25px; line-height:1;">✔</button>
                            </form>
                           <?php else: ?>
                     <span style="display:inline-block; width:75px; text-align:center;"><?= $row['max_students'] ?></span>
                 <?php endif; ?>
        </td>

                          <td style="padding:6px;">
                              <?php if ($row['id'] > 3) : ?>
                                  <a href="home.php?delete_lab=<?php echo $row['id']; ?>" 
                                  onclick="return confirm('Διαγραφή εργαστηρίου;')" 
                                  style="color:red;">🗑</a>
                                  <?php else: ?>
                                            —
                                <?php endif; ?>
                          </td>
                </tr>
            <?php endwhile; ?>  
                

                </tbody>
            </table>

            
               
             <form method="POST" style="margin-top:15px;">
                 <input type="text" name="name" placeholder="Όνομα Εργαστηρίου">
                 <input type="text" name="day" placeholder="Ημέρα (π.χ. Δευτέρα)">
                 <input type="text" name="time" placeholder="Ώρα (π.χ. 10:00)">
                 <input type="number" name="max_students" placeholder="Μέγιστοι φοιτητές">
                 <button type="submit" name="add_lab">➕ Προσθήκη</button>
             </form>

               

					
				<?php } ?> 
			</div>
		</div>
	</div>
</body>
</html>