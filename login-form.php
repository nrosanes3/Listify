<form action="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']);?>" method="POST" class="login-form form">
    <div>
        <label for="email">Email or User Name</label>
        <input type="text" id="email" name="email" value="<?php echo $email;?>">
    </div>

    <div>
        <label for="password">Password</label>
        <input type="text" id="password" name="password" value="<?php echo $password;?>">
    </div>

    <div><input type="submit" name="login-btn" value="Login"></div>
</form>
