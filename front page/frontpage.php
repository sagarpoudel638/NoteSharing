<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /project-main/login/login.html");
    exit();
}

$conn = mysqli_connect("localhost", "root", "", "note_sharing");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$user_name = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Dhurbatara Notes Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Styles -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0d0d1a;
            color: #f5f5f5;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        header {
            background-color: #1F7CEC;
            padding: 1rem 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .site-name {
            font-size: 1.8rem;
            color: #fff;
            font-weight: bold;
        }

        .logout-btn {
            background-color: #dc3545;
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #a71d2a;
        }

        .notes-dashboard {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 1rem;
            text-align: center;
        }

        .notes-dashboard h2 {
            font-size: 2.4rem;
            color: #66E7EC;
            margin-bottom: 2rem;
        }

        .notes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .note-card {
            background: linear-gradient(145deg, #1c1c2e, #22223b);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .note-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.8);
        }

        .note-card h3 {
            color: #1F7CEC;
            font-size: 1.6rem;
            margin-bottom: 0.8rem;
        }

        .note-card p {
            color: #cccccc;
            margin-bottom: 1rem;
        }

        .note-category {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 1.2rem;
        }

        .view-btn {
            display: inline-block;
            background-color: #1F7CEC;
            color: #fff;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #155ab6;
        }

        /* Review Section */
        .review-section {
            margin-top: 1.5rem;
            text-align: left;
        }

        .review-section h4 {
            color: #66E7EC;
            margin-bottom: 0.5rem;
        }

        .stars input[type="radio"] {
            display: none;
        }

        .stars label {
            font-size: 1.5rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .stars label:hover,
        .stars label:hover ~ label,
        .stars input[type="radio"]:checked ~ label {
            color: #FFD700;
        }

        textarea {
            width: 100%;
            border-radius: 8px;
            padding: 0.6rem;
            border: none;
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .submit-review-btn {
            background-color: #66E7EC;
            color: #000;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .submit-review-btn:hover {
            background-color: #4bc9c6;
        }

        /* Display Reviews */
        .existing-reviews {
            margin-top: 1rem;
            background-color: #2c2c4a;
            padding: 1rem;
            border-radius: 8px;
        }

        .review-item {
            margin-bottom: 1rem;
            border-bottom: 1px solid #444;
            padding-bottom: 0.5rem;
        }

        .review-stars {
            color: #FFD700;
        }

        footer {
            background-color: #0a0a14;
            padding: 1rem;
            text-align: center;
            color: #777;
            font-size: 0.9rem;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                text-align: center;
            }

            .site-name {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>

<body>

    <!-- Header -->
    <header>
        <div class="header-container">
            <div class="logo">
                <h1 class="site-name">Dhurbatara Notes</h1>
            </div>
            <div>
                <span>Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </div>
    </header>

    <!-- Notes Section -->
    <section class="notes-dashboard">
        <h2>Available Notes</h2>
        <div class="notes-grid">

            <?php
            $notes_query = "SELECT * FROM notes";
            $notes_result = mysqli_query($conn, $notes_query);

            if (mysqli_num_rows($notes_result) > 0):
                while ($note = mysqli_fetch_assoc($notes_result)):
            ?>
                <div class="note-card">
                    <h3><?php echo htmlspecialchars($note['title']); ?></h3>
                    <p><?php echo htmlspecialchars($note['description']); ?></p>
                    <p class="note-category">Category: <strong><?php echo htmlspecialchars($note['category']); ?></strong></p>
                    <a href="/project-main/pages/<?php echo ($note['file_path']); ?>" target="_blank" class="view-btn">View Note</a>

                    <!-- Submit Review -->
                    <div class="review-section">
                        <h4>Submit Your Review</h4>
                        <form action="/project-main/pages/submit_review.php" method="post">
                            <input type="hidden" name="note_id" value="<?php echo $note['id']; ?>">
                            
                            <div class="stars">
                                <input type="radio" id="star5-<?php echo $note['id']; ?>" name="rating" value="5" required>
                                <label for="star5-<?php echo $note['id']; ?>">★</label>
                                <input type="radio" id="star4-<?php echo $note['id']; ?>" name="rating" value="4">
                                <label for="star4-<?php echo $note['id']; ?>">★</label>
                                <input type="radio" id="star3-<?php echo $note['id']; ?>" name="rating" value="3">
                                <label for="star3-<?php echo $note['id']; ?>">★</label>
                                <input type="radio" id="star2-<?php echo $note['id']; ?>" name="rating" value="2">
                                <label for="star2-<?php echo $note['id']; ?>">★</label>
                                <input type="radio" id="star1-<?php echo $note['id']; ?>" name="rating" value="1">
                                <label for="star1-<?php echo $note['id']; ?>">★</label>
                            </div>

                            <textarea name="review" rows="3" placeholder="Write your review..." required></textarea>
                            <button type="submit" class="submit-review-btn">Submit Review</button>
                        </form>
                    </div>

                    <!-- Existing Reviews -->
                   <!-- Existing Reviews -->
<div class="existing-reviews">
    <h4>Reviews</h4>

    <?php
    $review_query = "SELECT * FROM reviews WHERE note_id = " . $note['id'];
    $review_result = mysqli_query($conn, $review_query);

    if (mysqli_num_rows($review_result) > 0):
        while ($review = mysqli_fetch_assoc($review_result)):
    ?>
            <div class="review-item">
                <!-- Star Ratings -->
                <div class="review-stars">
                    <?php
                    $rating = intval($review['rating']);
                    for ($i = 1; $i <= 5; $i++) {
                        if ($i <= $rating) {
                            echo '★';
                        } else {
                            echo '☆';
                        }
                    }
                    ?>
                </div>

                <!-- Review Comment -->
                <p style="margin-top: 5px; color: #ddd;">
                    <?php echo htmlspecialchars($review['review']); ?>
                </p>

                <!-- Reviewer Info (optional) -->
                <small style="color: #999;">By User ID: <?php echo htmlspecialchars($review['user_name']); ?></small>
            </div>
    <?php
        endwhile;
    else:
    ?>
        <p style="color: #aaa;">No reviews yet. Be the first to review this note!</p>
    <?php endif; ?>
</div>


                </div>
            <?php
                endwhile;
            else:
                echo "<p>No notes available at the moment.</p>";
            endif;
            ?>

        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Dhurbatara Notes. All rights reserved.</p>
    </footer>

</body>
</html>

<?php mysqli_close($conn); ?>
