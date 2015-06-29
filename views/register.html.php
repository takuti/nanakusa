<?php
$flash = flash_now();
if (isset($flash['error'])) {
?>
  <div id="notice-message" class="alert alert-danger" role="alert"><?php echo $flash['error'] ?></div>
<?php
}
?>

<form class="form-signin" action="<?php echo url_for('/create_user'); ?>" method="POST">
  <h2 class="form-signin-heading">Register</h2>
  <label class="sr-only">User ID</label>
  <input type="text" class="form-control" name="user_id" placeholder="Your user id" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" class="form-control" name="password"" placeholder="Your password" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
</form>
