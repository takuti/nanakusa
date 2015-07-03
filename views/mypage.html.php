<h2><?php echo get('user')['user_id'] ?> <a class="btn btn-info btn-xs" href="<?php echo url_for('/logout'); ?>" role="button">Logout</a></h2>

<div id="loading"></div>
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
<form action="<?php echo url_for('/create_repository'); ?>" method="POST" novalidate>
  <input type="text" class="form-control" name="repo_name" placeholder="New repository name" required>
  <button class="loading btn btn-primary btn-block" type="submit" onclick="activityIndicator();">Create</button>
</form>
<br />
<table class="table table-bordered table-striped">
  <thead>
  <tr>
    <th>Repository Name</th>
    <th>Git</th>
    <th>URL</th>
  </tr>
  </thead>
  <tbody>
    <?php
    foreach (get('repositories') as $repository) {
      $u = $repository['user_id'];
      $r = $repository['repo_name'];
      $git = "git@157.82.3.165:".$u."/".$r.".git";
      $url = "http://".$u.".".$r.".p61.exp.ci.i.u-tokyo.ac.jp";
      echo "<tr><td>".$r."</td><td>".$git."</td><td><a href=\"".$url."\">".$url."</a></td></tr>";
    }
    ?>
  </tbody>
</table>

<script src="assets/js/spin.min.js"></script>
<script>
function activityIndicator() {
  var target = document.getElementById('loading');
  var spinner = new Spinner().spin();
  target.style.display = 'block';
  spinner.spin(target);
}
</script>
