<?php 
require 'inc/header.php'; 

if(isset($_SESSION['id'])) header("Location: /");
?>

  <div class="jumbotron text-center">
    <h1 class="display-4">Register</h1>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-8 offset-2">
        <?php if(isset($_GET['verified'])): ?>
          <div class="alert alert-primary" role="alert"><?= $_GET['verified']; ?></div>
        <?php endif; ?>
        <div id="register_errors"></div>

        <form id="register_form">
          <input type="text" name="first_name" placeholder="Enter your first name..." class="form-control mb-3">
          <input type="text" name="last_name" placeholder="Enter your last name..." class="form-control mb-3">
          <input type="date" name="date_of_birth" class="form-control mb-3">
          <textarea name="bio" class="form-control mb-3" placeholder="Enter your bio..."></textarea>
          <select name="status" class="form-control mb-3">
            <option value="public" selected>public</option>
            <option value="private">private</option>
          </select>
          <input type="text" name="username" placeholder="Enter your username..." class="form-control mb-3">
          <input type="email" name="email" placeholder="Enter your email..." class="form-control mb-3">
          <input type="password" name="register_password" placeholder="Enter your password..." class="form-control mb-3" autocomplete="on">
          <input type="password" name="confirm_password" placeholder="Confirm your password..." class="form-control mb-3" autocomplete="on">
          <button class="btn btn-primary form-control mb-3" id="registerBtn" onclick="register(e)">Register</button>
        </form>
      </div>
    </div>
  </div>

<?php require 'inc/footer.php'; ?>