<?php

require_once 'limonade/lib/limonade.php';

function configure() {
  # read DB info from config.php or default
  if (file_exists('config.php')) {
    require_once 'config.php';
  } else {
    $host = 'localhost';
    $port = 8889;
    $username = 'root';
    $password = 'root';
    $dbname = 'nanakusa';
  }

  # create DB connection and save it
  try{
    $db = new PDO(
      "mysql:host=$host;port=$port;dbname=$dbname",
      $username,
      $password
    );
  } catch(PDOException $e) {
    halt('Connection faild: '.$e->getMessage());
  }
  option('db_connection', $db);
}

function get($key) {
  return set($key);
}

function before() {
  layout('layout.html.php');
}

function get_hashed_password($password, $salt) {
  return hash('sha256', "$password:$salt");
}

function login($user_id, $password) {
  $db = option('db_connection');
  $stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  $msg = 'Wrong username or password';

  # given user_id does not exist
  if (empty($user)) return ['status' => 'error', 'message' => $msg];

  # compare hashed password
  if (get_hashed_password($password, $user['salt']) == $user['password']) {
    return ['status' => 'success', 'user' => $user];
  } else {
    return ['status' => 'error', 'message' => $msg];
  }
}

function current_user() {
  if (empty($_SESSION['user_id'])) return null;

  $db = option('db_connection');
  $stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
  $stmt->bindValue(':user_id', $_SESSION['user_id']);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (empty($user)) {
    unset($_SESSION['user_id']);
    return null;
  }
  return $user;
}

function get_repositories($user_id) {
  # get user's all repositories
  $db = option('db_connection');
  $stmt = $db->prepare('SELECT * FROM repositories WHERE user_id = :user_id');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $repositories = $stmt->fetchAll(PDO::FETCH_ASSOC);

  return $repositories;
}

function create_repository($user_id, $repo_name) {
  # validate repository name
  if (!preg_match('/^[a-zA-Z]+$/', $repo_name)) {
    return ['status' => 'error', 'message' => 'Alphabet only'];
  }

  $db = option('db_connection');

  # check whether same repository exists
  $stmt = $db->prepare('SELECT * FROM repositories WHERE user_id = :user_id AND repo_name = :repo_name');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':repo_name', $repo_name);
  $stmt->execute();
  $repository = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!empty($repository)) {
    return ['status' => 'error', 'message' => "Repository `$repo_name` already exists"];
  }

  # create repository
  $stmt = $db->prepare('INSERT INTO repositories (user_id, repo_name) VALUES (:user_id, :repo_name)');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':repo_name', $repo_name);
  $stmt->execute();

  # TODO: write atomic controlled shellscript execution here!!!

  return ['status' => 'success', 'message' => "Repository `$repo_name` has been successfully created"];
}

function create_user($user_id, $password) {
  # user id and password must be specified
  if (empty($user_id) || empty($password)) {
    return ['status' => 'error', 'message' => 'User ID and/or password are missing'];
  }

  # validate user id
  if (!preg_match('/^[a-zA-Z]+$/', $user_id)) {
    return ['status' => 'error', 'message' => 'User id only allows alphabet'];
  }

  $db = option('db_connection');

  # check whether same user exists
  $stmt = $db->prepare('SELECT * FROM users WHERE user_id = :user_id');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!empty($user)) {
    return ['status' => 'error', 'message' => "User `$user_id` already exists"];
  }

  # user's salt is hashed value of the user_id
  $salt = hash('sha256', $user_id);
  $hashed_password = get_hashed_password($password, $salt);

  $stmt = $db->prepare('INSERT INTO users (user_id, salt, password) VALUES (:user_id, :salt, :hashed_password)');
  $stmt->bindValue(':user_id', $user_id);
  $stmt->bindValue(':salt', $salt);
  $stmt->bindValue(':hashed_password', $hashed_password);
  $stmt->execute();

  return ['status' => 'success', 'message' => "You have successfully registered as `$user_id`"];
}

dispatch_get('/', function() {
  $user = current_user();
  if (empty($user)) {
    return html('index.html.php');
  } else {
    set('repositories', get_repositories($user['user_id']));
    set('user', $user);
    return html('mypage.html.php');
  }
});

dispatch_get('/register', function() {
  return html('register.html.php');
});

dispatch_post('/create_user', function() {
  $result = create_user($_POST['user_id'], $_POST['password']);
  switch($result['status']) {
    case 'error':
      flash('error', $result['message']);
      return redirect_to('/register');
    case 'success':
      flash('success', $result['message']);
      return redirect_to('/');
  }
});

dispatch_post('/login', function() {
  $result = login($_POST['user_id'], $_POST['password']);

  if (!empty($result['user'])) {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $result['user']['user_id'];
    return redirect_to('/mypage');
  } else {
    flash('error', $result['message']);
    return redirect_to('/');
  }
});

dispatch_get('/logout', function() {
  session_destroy();
  return redirect_to('/');
});

dispatch_get('/mypage', function() {
  $user = current_user();
  if (empty($user)) {
    flash('error', 'You must be logged in');
    return redirect_to('/');
  }
  else {
    set('repositories', get_repositories($user['user_id']));
    set('user', $user);
    return html('mypage.html.php');
  }
});

dispatch_post('/create_repository', function() {
  $user = current_user();
  if (empty($user)) {
    flash('error', 'Something is wrong');
    return redirect_to('/');
  }

  $result = create_repository($user['user_id'], $_POST['repo_name']);
  flash($result['status'], $result['message']);
  return redirect_to('/mypage');
});

run();
