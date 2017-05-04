<div class="container">
    <form action="index.php?action=doLogin" method="post">
        <!-- email -->
        <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" name="email" required/>
        </div>

        <!-- password1 -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" required/>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
        </div>

        <div class="text-center">
            <a href="../oldfiles/input-user.php" type="button" class= "btn btn-default">Create a new account</a>
            <a href="#" onclick="history.back();" type="button" class= "btn btn-default">Back</a>
        </div>
    </form>
</div>
