<?php

session_start();

if(isset($_SESSION['user_id'])) {
  header('location: index.php');
  exit;
}

require_once 'app/helpers.php';
$page_title = 'Login';

$errors = [
    'email' => '',
    'password' => '',
    'submit' => '',
];

if( isset($_POST['submit']) ) {

  if( isset($_POST['token']) && isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token'] ) {

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $form_valid = true;

    if(!$email) {
      $form_valid = false;
      $errors['email'] = 'A valid email is required';
    }
    
    if(!$password || strlen($password) < 6 || strlen($password) > 20) {
      $form_valid = false;
      $errors['password'] = 'Password is required and must be at least 6 chars and less than 20 chars';
    }

    if($form_valid) {
      $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
      $email = mysqli_real_escape_string($link, $email);
      $password = mysqli_real_escape_string($link, $password);
      $sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
      $result = mysqli_query($link, $sql);
  
      if($result && mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if( password_verify($password, $user['password']) ) {

          $_SESSION['user_id'] = $user['id'];
          $_SESSION['user_name'] = htmlspecialchars($user['name']);
          header('location: ./');
          exit;

        } else { 

          $errors['submit'] = 'Wrong email or password'; 

        }
              
      } else {

        $errors['submit'] = 'Wrong email or password'; 
          
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
      <h1 class="title is-spaced is-capitalize">Login with your account</h1>
      <hr>
      <h2 class="subtitle">Don't have an account? <a href="signup.php">Sign up now!</a></h2>
    </section>

    <section class="section">
      <form method="POST" action="" class="form-wide" id="login-form" autocomplete="off" novalidate="novalidate">
        <input type="hidden" name="token" value="<?= $token; ?>">
        <div class="field">
          <label class="label" for="email">Email</label>
          <p class="control has-icons-left has-icons-right">
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

        <div class="field mt-5">
          <?php if($errors['submit']): ?>
          <div class="notification is-danger is-light"> <?= $errors['submit']; ?> </div>
          <?php endif; ?>
          <button type="submit" name="submit" class="button is-danger full-width">Login</button>
        </div>
      </form>
    </section>

  </div>
</main>
<?php include 'tpl/footer.php'; ?>