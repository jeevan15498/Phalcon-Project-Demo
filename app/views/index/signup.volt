<div class="container">
    {{ form("index/signup") }}
        <h2 class="form-signin-heading">User Sign-Up</h2>
        <label for="user" class="sr-only">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Full Name">
        <br>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" name="email" class="form-control" placeholder="Email address">
        <br>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
        <br>
        <label for="role" class="sr-only">User Role</label>
        <select class="form-control" name="role" id="user_role">
            <option value="">Select User Role</option>
            <option value="Registered User">User</option>
            <option value="Admin">Admin</option>
        </select>
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">User Sign-Up</button>
    </form>
</div>