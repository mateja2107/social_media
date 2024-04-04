<?php 
require 'inc/header.php'; 

if(isset($_SESSION['id'])) header("Location: /");
?>

  <div class="jumbotron text-center">
    <h1 class="display-4">Login</h1>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-8 offset-2">
        <div id="login_errors"></div>
        <form id="login_form">
          <input type="text" name="username_email" placeholder="Enter your username or email..." class="form-control mb-3">
          <input type="password" name="login_password" placeholder="Enter your password..." class="form-control mb-3" autocomplete="on">
          <input type="checkbox" name="remember" class=" mb-3"> <span class="mb-3">Remember me</span>
          <button class="btn btn-primary form-control mb-3" id="loginBtn" onclick="login()">Login</button>
        </form>
      </div>
    </div>
  </div>

<?php require 'inc/footer.php'; ?>