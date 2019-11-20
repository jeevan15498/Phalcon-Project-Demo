<div class="container">
    <form action="" method="POST">
        <h2 class="form-signin-heading">User Login</h2>        
        <label class="sr-only">Email address</label>
        <input type="email" name="email" class="form-control" placeholder="Email address">
        <br>
        <label class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">User Login</button>
    </form>
</div>

<?= "User Name  :  ". $this->session->get('AUTH_NAME') ?>
<br>
<?= "User Email  :  ". $this->session->get('AUTH_EMAIL') ?>
<br>
<?= "User Role  :  ". $this->session->get('AUTH_ROLE') ?>