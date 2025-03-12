<?php
// Database connection
$host = 'localhost'; 
$db = 'note_sharing'; 
$user = 'root'; 
$pass = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch notes and review counts
$query = "SELECT * FROM notes ORDER BY created_at DESC";
$stmt = $pdo->query($query);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

function getReviewCounts($pdo, $noteId) {
    $sql = "
        SELECT 
            COUNT(*) AS total_reviews,
            SUM(CASE WHEN approved = 'approved' THEN 1 ELSE 0 END) AS approved_reviews,
            SUM(CASE WHEN approved = 'pending' THEN 1 ELSE 0 END) AS pending_reviews
        FROM reviews
        WHERE note_id = :noteId
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':noteId', $noteId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Manage Notes</title>
  <link rel="stylesheet" href="upload.css" />
</head>
<body>
  <header>
    <h1>Admin Panel - Manage Notes</h1>
    <nav>
      <a href="upload_form.html">Upload New Note</a>
      <a href = "../admin%20panel/admin_users.html">View Users</a>
    </nav>
  </header>

  <div class="container">
    <h2>Uploaded Notes</h2>
    <?php if (isset($_GET['upload']) && $_GET['upload'] === 'success') : ?>
      <p class="success">✅ Note uploaded successfully!</p>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0">
      <thead>
        <tr>
          <th>Title</th>
          <th>Description</th>
          <th>Category</th>
          <th>File</th>
          <th>Total Reviews</th>
          <th>Approved Reviews</th>
          <th>Pending Reviews</th>
          <th>Review Details</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($notes)) : ?>
          <?php foreach ($notes as $note) : ?>
            <?php $reviewCounts = getReviewCounts($pdo, $note['id']); ?>
            <tr>
              <td><?= htmlspecialchars($note['title']); ?></td>
              <td><?= htmlspecialchars($note['description']); ?></td>
              <td><?= htmlspecialchars($note['category']); ?></td>
              <td><a href="<?= htmlspecialchars($note['file_path']); ?>" target="_blank">View PDF</a></td>
              <td><?= $reviewCounts['total_reviews'] ?? 0; ?></td>
              <td><?= $reviewCounts['approved_reviews'] ?? 0; ?></td>
              <td><?= $reviewCounts['pending_reviews'] ?? 0; ?></td>
              <td><a href="review.html?noteId=<?= $note['id']; ?>">Manage Reviews</a></td>
              <td>
                <a href="delete_note.php?id=<?= $note['id']; ?>" onclick="return confirm('Delete this note?');">Delete</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else : ?>
          <tr>
            <td colspan="9">No notes uploaded yet.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

<?php
$pdo = null;
?>
