<?php include '../../src/database/Database.php'                     ?>
<?php include '../../src/validation/Validator.php'                  ?>
<?php include '../../src/controllers/front/SignUpController.php'    ?>
<?php include '../layout/header.php'                                ?>

<div class="w-px-210 m-top-25 center">
    <form class="form" method="POST">
        
        <div class="placeholder placeholder--warning">
            <?php echo $message; ?>
        </div>
        <input type="text" name="username" placeholder="потребителско име">
        <input type="password" name="password" placeholder="парола">
        <input type="password" name="repeat_password" placeholder="парола">
        <input type="text" name="email" placeholder="E-mail">
        <input type="submit" value="влез">
        <input type="hidden" name="tokken" value="1">
    </form>
</div>


<?php include '../layout/footer.php' ?>