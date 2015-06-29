<!DOCTYPE html>
<html>
  <head>
    <title>Nanakusa Network</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="assets/css/nanakusa.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class="container">
      <div class="h1"><a href="<?php echo url_for('/'); ?>">Nanakusa Network</a></div>
      <?php echo $content ?>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>
