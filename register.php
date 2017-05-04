<div class="container">
    <form action="index.php?action=doRegister" method="post">
        <!-- customername -->
        <div class="form-group">
            <label for="customername">customername</label>
            <input type="text" class="form-control" name="customername" id="customername"/>
        </div>
        <!-- email -->
        <div class="form-group">
            <label for="email">email</label>
            <input type="email" class="form-control" name="email" id="email"/>
        </div>

        <!-- password1 -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password"/>
        </div>

        <!-- password2 -->
        <div class="form-group">
            <label for="password2">Password again</label>
            <input type="password" class="form-control" name="password2" id="password2"/>
        </div>

        <div class="form-group">
            <label for="phonenumber">Enter your phone number</label>
            <input type="text" class="form-control" name="phonenumber" id="phonenumber"/>
        </div>

        <div class="form-group">
            <label for="address">Enter your address</label>
            <input type="text" class="form-control" name="address" id="address"/>
        </div>

        <div class="form-group">
            <label for="creditcard">Enter your credit card</label>
            <input type="text" class="form-control" name="creditcard" id="creditcard"/>
        </div>

        <div class="form-group">
            <label for="prefdeliverydate">Enter your pref delivery date</label>
            <input type="date" class="form-control" name="prefdeliverydate" id="prefdeliverydate"/>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Add</button>
    </form>
</div>
