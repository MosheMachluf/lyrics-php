<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
  <link rel="stylesheet" href="css/bulma.min.css">
  <link rel="stylesheet" href="css/main.css">
  <title>Lyrics | <?= $page_title ?? '' ?></title>
</head>

<body>
  <header>
    <nav class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item" href="./">
            <i class="fas fa-music mr-2"></i>
            <strong>Lyrics</strong>
          </a>

          <a class="navbar-burger burger" data-target="navbarBasicExample">
            <span></span>
            <span></span>
            <span></span>
          </a>
        </div>

        <div id="navbarBasicExample" class="navbar-menu">
          <div class="navbar-start">
            <a class="navbar-item" href="./">Home</a>

            <a class="navbar-item" href="about.php">About</a>

          </div>

          <div class="navbar-end">
            <div class="navbar-item">
              <?php if( !isset( $_SESSION['user_id'] ) ): ?>
              <div class="buttons">
                <a class="button is-danger" href="signup.php">
                  <strong>Sign up</strong>
                </a>
                <a class="button is-light" href="login.php">
                  Log in
                </a>
              </div>

              <?php else: ?>

              <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link is-capitalized"> <?= $_SESSION['user_name']; ?> </a>
                <div class="navbar-dropdown">
                  <a href="profile.php?uid=<?= $_SESSION['user_id'] ?>" class="navbar-item">Profile</a>
                  <hr class="navbar-divider">
                  <a class="navbar-item has-text-danger" href="logout.php">Logout</a>
                </div>
              </div>

              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </nav>
  </header>