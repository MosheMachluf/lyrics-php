<?php

session_start();

$uid = $_SESSION['user_id'] ?? null;
if(!$uid) {
  header('location: login.php');
  exit;
}

$sid = !empty($_GET['sid']) ? trim($_GET['sid']) : null;
if(!$sid || !is_numeric($sid)) {
  header('location: ./');
  exit;
}

require_once 'app/helpers.php';

$link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
$sid = mysqli_real_escape_string($link, $sid);
$sql = "SELECT * FROM songs WHERE id = $sid AND user_id = $uid";
$result = mysqli_query($link, $sql);


if($result && mysqli_num_rows($result)) {
  $song = mysqli_fetch_assoc($result);
} else {
  header('location: ./');
  exit;
}

$page_title = 'Edit song';

$errors = [
    'title' => '',
    'lyrics' => '',
    'submit' => '',
];

if(isset($_POST['submit'])) {

  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);  
  $title = trim($title);
  $lyrics =  filter_input(INPUT_POST, 'lyrics', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);  
  $lyrics = trim($lyrics);
  $form_valid = true;
  
  if( !$title || mb_strlen($title) < 2 ) {
    $form_valid = false;
    $errors['title'] = 'Title must be at least 2 characters';
  }

  
  if( !$lyrics || mb_strlen($lyrics) < 2 ) {
    $form_valid = false;
    $errors['lyrics'] = 'Lyrics must be at least 2 characters';
  }

  if($form_valid) {

    $title = mysqli_real_escape_string($link, $title);
    $lyrics = mysqli_real_escape_string($link, $lyrics);
    $sql = "UPDATE songs SET title = '$title', lyrics = '$lyrics' WHERE id = $sid";
    $result = mysqli_query($link, $sql);
    header('location: ./');
    exit;

  }
    
}

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">

    <section class="section">
      <h1 class="title is-spaced is-capitalize">Edit song</h1>
      <hr>
      <h2 class="subtitle">Here you can edit your song, <strong>Note!</strong> after saving you will not be able to
        restore the changes to their previous state</h2>
    </section>

    <section class="section">
      <form method="POST" action="" class="form-wide" id="add-song-form" autocomplete="off" novalidate="novalidate">

        <div class="field">
          <label class="label" for="title">Title</label>
          <input class="input" type="text" name="title" id="title" value="<?= $song['title']; ?>">
          <p class="help is-danger"><?= $errors['title']; ?></p>
        </div>

        <div class="field">
          <label class="label" for="lyrics">Lyrics</label>
          <textarea class="textarea" name="lyrics" id="lyrics" cols="30" rows="10"><?= $song['lyrics']; ?></textarea>
          <p class="help is-danger"><?= $errors['lyrics']; ?></p>
        </div>


        <div class="field has-text-right">
          <?php if($errors['submit']): ?>
          <div class="notification is-danger is-light"> <?= $errors['submit']; ?> </div>
          <?php endif; ?>

          <a href="./" class="button">Cancel</a>
          <button type="submit" name="submit" class="button is-danger">Save</button>
        </div>
      </form>
    </section>

  </div>
</main>
<?php include 'tpl/footer.php'; ?>