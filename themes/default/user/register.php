<h1>Register page</h1>


<form @submit.prevent="onRegisterFormSubmit">


    <div class="form-group">
        <label for="register_user_email">Email address</label>
        <input type="email" class="form-control" id="register_user_email" name="user_email" aria-describedby="emailHelp" v-model="register.user_email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>

    <div class="form-group">
        <label for="register_user_pass">Password</label>
        <input type="password" class="form-control" id="register_user_pass" name="user_pass" v-model="register.user_pass">
    </div>

    <div class="form-group">
        <label for="register_nickname">Nickname</label>
        <input type="text" class="form-control" id="register_nickname" name="nickname" v-model="register.nickname">
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>


</form>