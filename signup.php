<?php

session_start();

if(isset($_SESSION['user_id'])) {
  header('location: ./');
  exit;
}

require_once 'app/helpers.php';
$page_title = 'Signup';

$errors = [
    'name' => '',
    'email' => '',
    'password' => '',
];

if( isset($_POST['submit']) ) {

  if( isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token'] ) {

    $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $name = mysqli_real_escape_string($link, $name);

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $email = mysqli_real_escape_string($link, $email);
    
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $password = mysqli_real_escape_string($link, $password);

    $form_valid = true;

    if( !$name || mb_strlen($name) < 2 || mb_strlen($name) > 70 ) {
      $form_valid = false;
      $errors['name'] = 'Name is required and must be at least 2 chars, and less than 70 chars';
    }

    if( !$email ) {
      $form_valid = false;
      $errors['email'] = 'A valid email is required';
    } elseif( email_exist($link, $email) ) { 
      $form_valid = false;
      $errors['email'] = 'Email is taken';
    }

    if( !$password || strlen($password) < 6 || strlen($password) > 20 ) {
      $form_valid = false;
      $errors['password'] = 'Password is required and must be at least 6 chars and less than 20 chars';
    }


    if($form_valid) {

      $profile_image = 'default-profile.png';
      $file_image = $_FILES['profile_image'];

      if( isset($file_image['error']) && $file_image['error'] == 0 && is_uploaded_file($file_image['tmp_name']) ) {
        
        $max_image_size = 1024 * 1024 * 3;
        $extensions = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];
        $types = ['image/png', 'image/jpg', 'image/jpeg', 'image/gif', 'image/bmp'];
        
        if( $file_image['size'] <= $max_image_size ) {
      
          $file_details = pathinfo($file_image['name']);
          
          if( in_array(strtolower($file_details['extension']), $extensions) ) {

            if( in_array(strtolower($file_image['type']), $types) ) {

              $profile_image =  $file_image['name'] . '-' . date('Y.m.d.H.i.s') . '-' . random_str();
              move_uploaded_file($file_image['tmp_name'], 'images/' . $profile_image);

            }
          
          }

        }

      }

      $password = password_hash($password, PASSWORD_BCRYPT);
      $sql = "INSERT INTO users VALUES(null, '$name', '$email', '$password','$profile_image')";
      $result = mysqli_query($link, $sql);

      if( $result && mysqli_affected_rows($link) ) {

        $_SESSION['user_id'] = mysqli_insert_id($link);
        $_SESSION['user_name'] = htmlspecialchars($name);
        header('location: ./');
        exit;

      }
    }

  }

    $token = csrf_token();
 
  } else {

    $token = csrf_token();
  
  }
  
?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">
    <section class="section">
      <h1 class="title is-spaced is-capitalize">Signup for new account</h1>
      <hr>
      <h2 class="subtitle">Have an account? <a href="login.php">Login now</a></h2>
    </section>

    <section class="section">

      <form method="POST" action="" class="form-wide" id="signup-form" autocomplete="off" novalidate="novalidate"
        enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?= $token; ?>">
        <div class="field">
          <label class="label" for="name">Full Name</label>
          <p class="control has-icons-left">
            <input class="input" type="text" name="name" id="name" placeholder="John Dou" value="<?= old('name'); ?>">
            <span class="icon is-small is-left">
              <i class="fas fa-user"></i>
            </span>
          </p>
          <p class="help is-danger"><?= $errors['name']; ?></p>
        </div>

        <div class="field">
          <label class="label" for="email">Email</label>
          <p class="control has-icons-left">
            <input class="input" type="email" name="email" id="email" placeholder="john@email.com"
              value="<?= old('email'); ?>">
            <span class="icon is-small is-left">
              <i class="fas fa-envelope"></i>
            </span>
          </p>
          <p class="help is-danger"><?= $errors['email']; ?></p>
        </div>

        <div class="field">
          <label class="label" for="password">Password</label>
          <p class="control has-icons-left">
            <input class="input" type="password" name="password" id="password" placeholder="******">
            <span class="icon is-small is-left">
              <i class="fas fa-lock"></i>
            </span>
          </p>
          <p class="help is-danger"><?= $errors['password']; ?></p>
        </div>

        <div class="field">
          <label class="label" for="profile-image">Profile image (optional)</label>
          <div class="file has-name">
            <label class="file-label grow-1">
              <input class="file-input" type="file" name="profile_image" id="profile-image">

              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Browse
                </span>
              </span>

              <div class="file-name grow-1">
                Choose a file…
              </div>
            </label>
          </div>
        </div>

        <div class="field mt-5">
          <button type="submit" name="submit" class="button is-danger full-width">Sign up</button>
        </div>
      </form>
    </section>

  </div>
</main>
<?php include 'tpl/footer.php'; ?>