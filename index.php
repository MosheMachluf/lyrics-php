<?php

session_start();
$page_title = 'Home Page';
require_once 'app/helpers.php';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
$sql = 'SELECT u.name AS user_name, u.profile_image, s.* FROM songs s
        JOIN users u ON u.id = s.user_id
        ORDER BY s.created_at DESC';
$result = mysqli_query($link, $sql);
$uid = $_SESSION['user_id'] ?? null;

?>

<?php include 'tpl/header.php'; ?>
<main>
  <section class="hero is-medium is-danger is-bold">
    <div class="container section my-5">
      <h1 class="title is-1 is-spaced">Welcome To Lyrics!</h1>

      <h2 class="subtitle is-4">
        Songwriter? Singer? this is the place for you!
        With us you will can publish your songs and find lyrics for any songs.
      </h2>
      <a href="about.php" class="button is-danger is-light">More Info</a>
    </div>
  </section>
  <div class="container section">

    <a href="add_song.php" class="button is-danger is-medium">
      <span class="icon is-small">
        <i class="fas fa-plus"></i>
      </span>
      <span>Add Your Song</span>
    </a>

    <div class="mt-5">
      <?php if($result && mysqli_num_rows($result)): ?>
      <?php while($song = mysqli_fetch_assoc($result)): ?>

      <div class="card mb-5">
        <header class="card-header">
          <a href="profile.php?uid=<?= $song['user_id'] ?>" class="card-header-title">
            <figure class="image is-32x32 mr-2">
              <img src="images/<?= $song['profile_image']; ?>" class="is-rounded"
                alt="<?= htmlspecialchars($song['name']); ?> profile">
            </figure>
            <?= htmlspecialchars($song['user_name']); ?>
          </a>

          <?php if($uid == $song['user_id']): ?>

          <div class="dropdown is-right">
            <a class="card-header-icon">
              <span class="icon">
                <i class="fas fa-angle-down"></i>
              </span>
            </a>
            <div class="dropdown-menu" id="more-options">
              <div class="dropdown-content">
                <a href="edit_song.php?sid=<?= $song['id']; ?>" class="dropdown-item">
                  <span class="icon"> <i class="fas fa-edit"></i> </span>
                  <span>Edit</span>
                </a>
                <a href="delete_song.php?sid=<?= $song['id']; ?>" class="dropdown-item confirm-delete">
                  <span class="icon"> <i class="fas fa-trash-alt"></i> </span>
                  <span>Delete</span>
                </a>
              </div>
            </div>
          </div>

          <?php endif; ?>
        </header>

        <div class="card-content">
          <div class="content">
            <h2 class="title"><?= htmlspecialchars($song['title']); ?></h2>
            <p class="lyrics-song hide"><?= htmlspecialchars($song['lyrics']); ?></p>
          </div>
        </div>

        <footer class="card-footer">
          <time class="card-footer-item" datetime="<?= $song['created_at']; ?>">
            <?= $song['created_at']; ?>
          </time>
        </footer>
      </div>

      <?php endwhile; ?>

      <?php else: ?>

      <h3>No songs yet...</h3>

      <?php endif; ?>
    </div>
  </div>

</main>
<?php include 'tpl/footer.php'; ?>