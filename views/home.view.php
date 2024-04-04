<?php require base_path('views/inc/header.php') ?>

  <div class="jumbotron text-center">
    <?php if(isset($_SESSION['id'])): ?>
      <h1 class="display-4">Home</h1>
    <?php else: ?>
      <h1 class="display-4">Welcome to Social Media</h1>
    <?php endif; ?>
  </div>

  <?php require base_path('views/inc/footer.php') ?>