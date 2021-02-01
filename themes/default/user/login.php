<h1>Login Page</h1>
<form @submit.prevent="onLoginFormSubmit">
    <div class="form-group">
        <label for="register_user_email">Email address</label>
        <input type="email" class="form-control" id="register_user_email" name="user_email" aria-describedby="emailHelp" v-model="login.user_email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
        <label for="register_user_pass">Password</label>
        <input type="password" class="form-control" id="register_user_pass" name="user_pass" v-model="login.user_pass">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<a class="btn btn-primary mt-5" href="<?=pass_login_url()?>">PASS LOGIN</a>

