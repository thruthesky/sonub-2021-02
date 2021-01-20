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
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="exampleCheck1">
            <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>


</form>