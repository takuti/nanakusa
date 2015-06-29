<h2>Hello, <?php echo get('user')['user_id'] ?>! <a class="btn btn-info btn-xs" href="<?php echo url_for('/logout'); ?>" role="button">Logout</a></h2>

<h3>Your repositories</h3>

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

<ul class="list-group">
<?php
foreach (get('repositories') as $repository) {
  echo "<li class=\"list-group-item\">".$repository['repo_name'].'</li>';
}
?>
</ul>

<form class="form-signin" action="<?php echo url_for('/create_repository'); ?>" method="POST">
  <h3 class="form-signin-heading">Create new repository</h3>
  <input type="text" class="form-control" name="repo_name" placeholder="Repository name" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
</form>
