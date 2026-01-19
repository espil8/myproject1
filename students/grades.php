<?php
include('functions.php');

if (isset($_POST['save_grades']) && isAdmin()) {
    $section_id = (int)$_POST['section_id'];
    foreach ($_POST['grades'] as $am => $grade) {
        if ($grade === '') {
            $grade = null;
        } else {
            $grade = floatval($grade);
            if ($grade < 0 || $grade > 10) continue;
        }
        $am = mysqli_real_escape_string($db, $am);
        $sql = "UPDATE registrations
                SET grade = " . ($grade === null ? "NULL" : $grade) . "
                WHERE am = '$am' AND section_id = $section_id";
        mysqli_query($db, $sql);
    }
    $_SESSION['success_message'] = 'Οι βαθμοί αποθηκεύτηκαν επιτυχώς';
    header("Location: grades.php?section_id=$section_id");
    exit();
}

if (!isLoggedIn()) {
    header('location: login.php');
    exit();
}

if (!isset($_GET['section_id'])) die("Μη έγκυρο εργαστήριο");

$section_id = (int)$_GET['section_id'];
$lab = getLabById($section_id);

if (!$lab) die("Το εργαστήριο δεν βρέθηκε");

$query = "SELECT u.am, u.email, r.grade
          FROM registrations r
          JOIN users u ON u.am = r.am
          WHERE r.section_id = $section_id
          ORDER BY u.am";
$result = mysqli_query($db, $query);
?>
<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title>Βαθμολογία - <?= htmlspecialchars($lab['name']) ?></title>
<link rel="stylesheet" href="style.css">
<style>
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    
}
th, td {
    border-bottom: 1px solid #ddd;
    padding: 8px;
    text-align: left;
    word-wrap: break-word;
}
th {
    background: #5F9EA0;
    color: white;
}
input[type=number] {
    width: 100%;
    max-width: 80px;
    
}
</style>
</head>
<body> 

<div class="content" style="width:70%; overflow:auto;">
    <?php if (isset($_SESSION['success_message'])): ?>
    <div style="
        background:#e6f7ee;
        color:#0f5132;
        padding:10px 15px;
        border-left:4px solid #198754;
        margin:15px 0;
        border-radius:4px;
        font-size:14px;
    ">
        ✅ <?= $_SESSION['success_message'] ?>
    </div>
    <?php unset($_SESSION['success_message']); endif; ?>

    <h2>Βαθμολογία – <?= htmlspecialchars($lab['name']) ?></h2>

    <?php if (isAdmin()): ?><form method="post"><?php endif; ?>

    

   <div style="display: block; border: 1px solid #ccc;">
    <table>
            <thead>
                <tr>
                    <th>ΑΜ</th>
                    <th>Email</th>
                    <th>Βαθμός</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['am']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td>
                        <?php if (isAdmin()): ?>
                        <input type="number"
                               name="grades[<?= htmlspecialchars($row['am']) ?>]"
                               step="0.1"
                               min="0"
                               max="10"
                               value="<?= $row['grade'] ?>">
                        <?php else: ?>
                        <?= $row['grade'] !== null ? $row['grade'] : '—' ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">Δεν υπάρχουν εγγεγραμμένοι φοιτητές</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isAdmin()): ?>
    <br><br>
    <input type="hidden" name="section_id" value="<?= $section_id ?>">
    <button type="submit" name="save_grades"
            style="padding:10px 20px; background:#003366; color:white; border:none; border-radius:6px;">
        💾 Αποθήκευση βαθμών
    </button>
    </form>
    <?php endif; ?>

    <br>
    <a href="lab.php?id=<?= $lab['id'] ?>">← Επιστροφή στο εργαστήριο</a>

</div>

</body>
</html>