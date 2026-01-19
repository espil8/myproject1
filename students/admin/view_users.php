<?php 
include('../functions.php');

if (!isAdmin()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: ../login.php');
}

// ΑΦΑΙΡΕΣΗ ΧΡΗΣΤΗ ΑΠΟ ΣΥΓΚΕΚΡΙΜΕΝΟ ΕΡΓΑΣΤΗΡΙΟ
if (isset($_GET['remove_from_lab']) && isset($_GET['section_id'])) {

    $am = mysqli_real_escape_string($db, $_GET['remove_from_lab']);
    $section_id = (int)$_GET['section_id'];

    mysqli_query(
        $db,
        "DELETE FROM registrations 
         WHERE am='$am' AND section_id=$section_id"
    );

    $_SESSION['success'] = "✅ Ο χρήστης αφαιρέθηκε από το εργαστήριο.";
    header("Location: view_users.php");
    exit();
}

// ===== DELETE USER LOGIC =====
if (isset($_GET['delete'])) {

    $am_to_delete = mysqli_real_escape_string($db, $_GET['delete']);

    // Ποτέ διαγραφή για τον admin
    $check = mysqli_query($db, "SELECT user_type FROM users WHERE am='$am_to_delete' LIMIT 1");
    $user = mysqli_fetch_assoc($check);

    if ($user && $user['user_type'] === 'admin') {
        $_SESSION['success'] = "❌ Δεν επιτρέπεται η διαγραφή διαχειριστή.";
        header("Location: view_users.php");
        exit();
    }

    // Διαγραφή registrations πρώτα
    mysqli_query($db, "DELETE FROM registrations WHERE am='$am_to_delete'");

    // Διαγραφή χρήστη
    mysqli_query($db, "DELETE FROM users WHERE am='$am_to_delete'");

    $_SESSION['success'] = "✅ Ο χρήστης διαγράφηκε επιτυχώς.";
    header("Location: view_users.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Προβολή Χρηστών</title>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <style>
        .users-box {
            width: 80%;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #B0C4DE;
            border-radius: 5px;
            background: #f9f9f9;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #5F9EA0;
            color: white;
        }


         .back-admin {
            display: inline-block;
            margin-bottom: 15px;
            padding: 8px 14px;
            background: #003366;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
          }

        .back-admin:hover {
         background: #005580;
         }





    </style>
</head>
<body>
    <div class="users-box">
        <h3>Λίστα Χρηστών</h3>

        <?php if (isset($_SESSION['success'])): ?>
            <div style="color:green; margin-bottom: 10px;">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>

        <table>
            <thead>
               <tr>
                 <th>ΑΜ</th>
                 <th>Email</th>
                 <th>Τύπος</th>
                 <th>Εργαστήριο</th>
                 <th>Ενέργεια</th>
               </tr>
            </thead>
            <tbody>
                <?php

                     $query = "
                     SELECT 
                       u.am,
                       u.email,
                       u.user_type,
                       s.id AS section_id,
                       s.name,
                       s.day,
                       s.time
                    FROM users u
                    LEFT JOIN registrations r ON u.am = r.am
                    LEFT JOIN sections s ON r.section_id = s.id
                    "; 

                $result = mysqli_query($db, $query);

                $users = [];

               while ($row = mysqli_fetch_assoc($result)) {

                  $am = $row['am'];

                  if (!isset($users[$am])) {
                        $users[$am] = [
                        'am' => $row['am'],
                        'email' => $row['email'],
                        'user_type' => $row['user_type'],
                        'labs' => []
                        ];
                  }

                   if (!empty($row['section_id'])) {
                       $users[$am]['labs'][] = [
                        'id' => $row['section_id'],
                        'name' => $row['name'],
                        'day' => $row['day'],
                        'time' => $row['time']
                       ];
                   }
           } 
?>

<?php foreach ($users as $user): ?>
<tr>
    <td><?= $user['am'] ?></td>
    <td><?= $user['email'] ?></td>
    <td><?= $user['user_type'] ?></td>

    <td>
    <?php if (!empty($user['labs']) && is_array($user['labs'])): ?>
        <ul style="margin:0; padding-left:15px;">
            <?php foreach ($user['labs'] as $lab): ?>
                <?php if (!empty($lab) && is_array($lab)): ?>
                    <li> 
                        <?= isset($lab['name']) ? htmlspecialchars($lab['name']) : '—' ?> 
                        (<?= isset($lab['day']) ? $lab['day'] : '-' ?> <?= isset($lab['time']) ? $lab['time'] : '-' ?>)
                        <a href="view_users.php?remove_from_lab=<?= $user['am'] ?>&section_id=<?= $lab['id'] ?? '' ?>" 
                        style="color:#FF8C09; margin-left:6px; " onclick="return confirm('Αφαίρεση από εργαστήριο;')"> 🔥Διαγραφή από το εργαστήριο 
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        —
    <?php endif; ?>
</td>

    <td>
        <?php if ($user['user_type'] !== 'admin'): ?>
            <a href="view_users.php?delete=<?= $user['am'] ?>"
               style="color:red;"
               onclick="return confirm('Διαγραφή ΟΛΟΚΛΗΡΟΥ του χρήστη;')">
               🚫 Διαγραφή χρήστη
            </a>
        <?php else: ?>
            —
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

      
            </tbody>

                   <a href="home.php" class="back-admin">
                    ← Επιστροφή στη σελίδα διαχειριστή
                   </a>
        </table>
    </div>
</body>
</html> 