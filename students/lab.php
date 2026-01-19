<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include('functions.php'); 

$section_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ADD FOLDER (ADMIN)
if (isAdmin() && isset($_POST['add_folder'])) {
    $title = trim($_POST['folder_title']);
    if (!empty($title)) {
        addLabFolder($section_id, $title);
    }
    header("Location: lab.php?id=" . $section_id);
    exit();
}


// DELETE FOLDER (ADMIN)
if (isAdmin() && isset($_GET['delete_folder'])) {
    $folder_id = (int)$_GET['delete_folder'];
    deleteLabFolder($folder_id);
    header("Location: lab.php?id=" . $section_id);
    exit();
}


// UPLOAD PDF (ADMIN)
if (isAdmin() && isset($_POST['upload_pdf'])) {
    $folder_id = (int)$_POST['folder_id'];
    uploadLabFile($folder_id, $_FILES['pdf_file']);

    header("Location: lab.php?id=" . $section_id);
    exit();
}


// DELETE PDF (ADMIN)
if (isAdmin() && isset($_GET['delete_file'])) {
    $file_id = (int)$_GET['delete_file'];
    deleteLabFile($file_id);

    header("Location: lab.php?id=" . $section_id);
    exit();
} 

// Έλεγχος αν είσαι logged in
if (!isset($_SESSION['user'])) {
    header('location: ../login.php');
    exit();
}



// Παίρνουμε το ID του εργαστηρίου από το URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Μη έγκυρο εργαστήριο.");
}

$lab_id = (int)$_GET['id'];
$lab = getLabById($lab_id);

if (!$lab) {
    die("Το εργαστήριο δεν βρέθηκε.");
}
?>

<!DOCTYPE html>
<html lang="el">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($lab['name']); ?></title>
<link rel="stylesheet" href="../style.css">

<style>
body {
    margin: 0;
    background: #f2f2f2;
    font-family: Georgia, "Times New Roman", serif;
    color: #222;
}

.page-wrapper {
    max-width: 1100px;
    margin: 30px auto;
    background: #fff;
    padding: 25px 40px;
}

.breadcrumb {
    font-size: 14px;
    color: #666;
    margin-bottom: 10px;
}

.breadcrumb a {
    color: #003366;
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.lab-title {
    font-size: 30px;
    margin: 10px 0 20px;
    line-height: 1.3;
}

.meta-bar {
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
    padding: 10px 0;
    margin-bottom: 30px;
    font-size: 15px;
    display: flex;
    gap: 30px;
}

.meta-bar span {
    color: #555;
}

.content-layout {
    display: grid;
    grid-template-columns: 3fr 1fr;
    gap: 40px;
}

.article-section h3 {
    font-size: 20px;
    margin-top: 0;
    border-bottom: 2px solid #003366;
    padding-bottom: 5px;
}

.article-section p {
    line-height: 1.8;
    font-size: 17px;
}

.sidebar {
    border-left: 1px solid #ddd;
    padding-left: 20px;
}

.sidebar h4 {
    margin-top: 0;
    font-size: 18px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 5px;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar li {
    margin-bottom: 10px;
    font-size: 15px;
}

.back-link {
    margin-top: 40px;
    display: inline-block;
    font-size: 15px;
    text-decoration: none;
    color: #003366;
}

.back-link:hover {
    text-decoration: underline;
}
</style>
</head>

<body>

<div class="page-wrapper">

    <div class="breadcrumb">
        <a href="<?php echo isAdmin() ? 'admin/home.php' : 'dashboard.php'; ?>">Αρχική</a>
        › Εργαστήρια
    </div>

    <h1 class="lab-title">
        <?php echo htmlspecialchars($lab['name']); ?>
    </h1>

    <div class="meta-bar">
        <span>📅 Ημέρα: <?php echo $lab['day']; ?></span>
        <span>⏰ Ώρα: <?php echo $lab['time']; ?></span>
        <span>👥 Μέγιστοι Φοιτητές: <?php echo $lab['max_students']; ?></span>
    </div>

    <div class="content-layout">

        <!-- ΚΥΡΙΟ ΠΕΡΙΕΧΟΜΕΝΟ -->
        <div class="article-section">
            <h3>Περιγραφή Εργαστηρίου</h3>
            <p>
                Το συγκεκριμένο εργαστήριο αποτελεί μέρος του μαθήματος και
                επικεντρώνεται στην πρακτική εφαρμογή της θεωρίας. Οι φοιτητές
                θα συμμετέχουν σε ασκήσεις, παραδείγματα και δραστηριότητες
                που στοχεύουν στην κατανόηση βασικών εννοιών.
            </p>

            <h3>Ανακοινώσεις</h3>
            <p>
                Δεν υπάρχουν ανακοινώσεις προς το παρόν. Οποιαδήποτε
                ενημέρωση θα εμφανίζεται σε αυτή την ενότητα.
            </p>
        </div>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h4>Πληροφορίες</h4>
            <ul>
                <li>📌 Υποχρεωτική παρακολούθηση</li>
                <li>📄 Υλικό: σύντομα διαθέσιμο</li>
                <li>📝 Αξιολόγηση μέσω εργασιών</li>
            </ul>


           <hr>
<h5>📂 Έγγραφα Εργαστηρίου</h5>

<?php if (isAdmin()): ?>
<form method="post" style="margin-bottom:15px;">
    <input type="text" name="folder_title" placeholder="Όνομα φακέλου" required>
    <button type="submit" name="add_folder">➕ Προσθήκη φακέλου</button>
</form>
<?php endif; ?>

<?php
$folders = getLabFolders($section_id);
?>

<?php if (mysqli_num_rows($folders) > 0): ?>
    <ul style="list-style:none; padding-left:0;">
        <?php while ($folder = mysqli_fetch_assoc($folders)): ?>
            <li style="padding:10px; border:1px solid #ccc; margin-bottom:8px; background:#f9f9f9;">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <strong>📁 <?= htmlspecialchars($folder['title']) ?></strong>

        <?php if (isAdmin()): ?>
            <a href="lab.php?id=<?= $section_id ?>&delete_folder=<?= $folder['id'] ?>"
               style="color:red; text-decoration:none;"
               onclick="return confirm('Διαγραφή φακέλου και όλων των αρχείων;')">
               🗑
            </a>
        <?php endif; ?>
    </div>

    <!-- FILES -->
    <?php
        $files = getFolderFiles($folder['id']);
    ?>

    <?php if (mysqli_num_rows($files) > 0): ?>
        <ul style="margin-top:8px;">
            <?php while ($file = mysqli_fetch_assoc($files)): ?>
                <li style="display:flex; justify-content:space-between; align-items:center;">
                  <span>
                      📄 
                    <a href="uploads/labs/<?= $file['filename'] ?>" target="_blank">
                     <?= htmlspecialchars($file['original_name']) ?>
                   </a>
                 </span>

            <?php if (isAdmin()): ?>
              <a href="lab.php?id=<?= $section_id ?>&delete_file=<?= $file['id'] ?>"
                style="color:red; text-decoration:none; margin-left:10px;"
                onclick="return confirm('Διαγραφή PDF;')">
                🗑
              </a>
            <?php endif; ?>
         </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p style="margin:6px 0;">— Δεν υπάρχουν αρχεία</p>
    <?php endif; ?>

    <!-- UPLOAD FORM (ADMIN ONLY) -->
    <?php if (isAdmin()): ?>
        <form method="post" enctype="multipart/form-data" style="margin-top:8px;">
            <input type="hidden" name="folder_id" value="<?= $folder['id'] ?>">
            <input type="file" name="pdf_file" accept="application/pdf" required>
            <button type="submit" name="upload_pdf">⬆ Ανέβασμα PDF</button>
        </form>
    <?php endif; ?>

</li>
        <?php endwhile; ?>
    </ul>
<?php else: ?>
    <p>Δεν υπάρχουν φάκελοι ακόμα.</p>
<?php endif; ?>





           <a href="grades.php?section_id=<?= $lab['id'] ?>"
            style="
           display:block;
           margin-top:15px;
           padding:10px;
           background:#003366;
           color:#fff;
           text-align:center;
           text-decoration:none;
           border-radius:6px;
           font-weight:bold;">
           📊 Βαθμολογία
         </a>
        </aside>


    </div>

    <a href="<?php echo isAdmin() ? 'admin/home.php' : 'dashboard.php'; ?>" class="back-link">
        ← Επιστροφή
    </a>

</div>

</body>
</html>