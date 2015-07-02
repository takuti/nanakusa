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

<table class="table table-bordered table-striped">
  <thead>
  <tr>
    <th>Repository Name</th>
    <th>Git</th>
  </tr>
  </thead>
  <tbody>
    <?php
    foreach (get('repositories') as $repository) {
      $r = $repository['repo_name'];
      $n = $repository['user_id'];
      echo "<tr><td>".$r."</td><td>git@157.82.3.165:".$n."/".$r.".git</td></tr>";
    }
    ?>
  </tbody>
</table>

<form class="form-signin" action="<?php echo url_for('/create_repository'); ?>" method="POST">
  <h3 class="form-signin-heading">Create new repository</h3>
  <input type="text" class="form-control" name="repo_name" placeholder="Repository name" required>
  <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
</form>
