<h1>New Product</h1>

<form action="<?= WEB_ROOT ?>products/<?= $product["id"] ?>/delete" method="post">

<!-- include the form contents from the new form.php -->
<?php require "form.php" ?>

</form>

</body>
</html>