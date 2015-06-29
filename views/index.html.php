<?php
$flash = flash_now();
if (isset($flash['success'])) {
?>
  <div id="notice-message" class="alert alert-success" role="alert"><?php echo $flash['success'] ?></div>
<?php
} else if (isset($flash['error'])) {
?>
  <div id="notice-message" class="alert alert-danger" role="alert"><?php echo $flash['error'] ?></div>
<?php
}
?>
<form class="form-signin" action="<?php echo url_for('/login'); ?>" method="POST">
  <h2 class="form-signin-heading">Please sign in</h2>
  <label class="sr-only">User ID</label>
  <input type="text" class="form-control" name="user_id" placeholder="user id" required autofocus>
  <label for="inputPassword" class="sr-only">Password</label>
  <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>

<a href="<?php echo url_for('/register'); ?>" >Sign up</a>
