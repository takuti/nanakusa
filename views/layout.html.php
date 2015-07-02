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
      <a href="<?php echo url_for('/'); ?>"><img  class="img-responsive" src="assets/img/nanakusa.jpg" alt="nanakusa" /></a>
      <?php echo $content ?>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>
