<?php

session_start();

if(!isset($_SESSION['user_id'])) {
  header('location: login.php');
  exit;
}

require_once 'app/helpers.php';

$page_title = 'Create a new song';
$errors = [
    'title' => '',
    'lyrics' => '',
    'submit' => '',
];

if(isset($_POST['submit'])) {

  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);  
  $title = trim($title);
  $lyrics = filter_input(INPUT_POST, 'lyrics', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);  
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

    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    /* if the content saved in Gibrish in mysql db 
    * option 1: mysqli_query($link, 'SET NAMES utf8');
    * option 2: mysqli_set_charset('uft8'); */
    $title = mysqli_real_escape_string($link, $title);
    $lyrics = mysqli_real_escape_string($link, $lyrics);
    $uid = $_SESSION['user_id'];
    $sql = "INSERT INTO songs VALUES(null, $uid, '$title', '$lyrics', NOW())";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_affected_rows($link) ) {

      header('location: ./');
      exit;

    } else {

      $errors['submit'] = 'An error occurred, please try again later.';

    }

  }
    
}

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">

    <section class="section">
      <h1 class="title is-spaced">Add your song</h1>
      <hr>
      <h2 class="subtitle">When you click publish button, your song will appear as public</h2>
    </section>

    <section class="section">
      <form method="POST" action="" class="form-wide" id="add-song-form" autocomplete="off" novalidate="novalidate">

        <div class="field">
          <label class="label" for="title">Title</label>
          <input class="input" type="text" name="title" id="title" value="<?= old('title'); ?>">
          <p class="help is-danger"><?= $errors['title']; ?></p>
        </div>

        <div class="field">
          <label class="label" for="lyrics">Lyrics</label>
          <textarea class="textarea" name="lyrics" id="lyrics" cols="30" rows="10"><?= old('lyrics'); ?></textarea>
          <p class="help is-danger"><?= $errors['lyrics']; ?></p>
        </div>


        <div class="field has-text-right">
          <?php if($errors['submit']): ?>
          <div class="notification is-danger is-light"> <?= $errors['submit']; ?> </div>
          <?php endif; ?>

          <a href="./" class="button">Cancel</a>
          <button type="submit" name="submit" class="button is-danger">Publish</button>
        </div>
      </form>
    </section>

  </div>
</main>
<?php include 'tpl/footer.php'; ?>