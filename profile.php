<?php

session_start();

if(!isset($_SESSION['user_id'])) {
  header('location: login.php');
  exit;
}

require_once 'app/helpers.php';
$page_title;
$uid = !empty($_GET['uid']) ? trim($_GET['uid']) : null;

if (is_numeric($uid)) {
  $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
  $uid = mysqli_real_escape_string($link, $uid);
  $sql = "SELECT u.*, COUNT(s.title) AS count_songs
          FROM users u
          JOIN songs s ON u.id = s.user_id
          WHERE u.id = $uid";
  $result = mysqli_query($link, $sql);

  if ($result && mysqli_num_rows($result)) {
    $user = mysqli_fetch_assoc($result);
    $page_title = $user['name'] . ' profile';
  } else {
    header('location: 404.php');
  }

} else {
  header('location: ./');
}

?>

<?php include 'tpl/header.php'; ?>
<main>
  <div class="container">
    <section class="section">
      <a href="./" class="button is-inline-block mr-5">
        <span class="icon"><i class="fas fa-arrow-circle-left"></i></span>
        <span>Back</span>
      </a>
      <h1 class="title is-spaced is-inline"><?= $user['name']; ?></h1>
      <hr>
    </section>

    <section class="is-flex" style="justify-content: space-between;">
      <picture style="border: 1px solid #ccc; width: 40%;">
        <img src="images/<?= $user['profile_image'] ?>" alt="<?= $user['name']; ?>">
      </picture>
      <table class="table is-bordered is-hoverable" style="flex: 0 0 50%;">
        <tr>
          <th>Name</th>
          <td><?= $user['name'] ?></td>
        </tr>
        <tr>
          <th>Email</th>
          <td><?= $user['email'] ?></td>
        </tr>
        <tr>
          <th>Number of songs</th>
          <td><?= $user['count_songs'] ?></td>
        </tr>
      </table>
    </section>
  </div>
</main>
<?php include 'tpl/footer.php'; ?>