<?php
$user = profile(in('ID'));
?>



<?php

d($user);

?>


<div id="register-page" class="container py-3">
            <h1>User Update</h1>
            <form class="register" @submit.prevent="onUserEdiFormSubmit($event)">
                <input type="hidden" name="session_id" value="<?=$user['session_id']??''?>">
                <div class="mb-3">
                    <label  class="form-label">Email address</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" value="<?=$user['user_email']??''?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="user_pass">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label  class="form-label">First name</label>
                                <input type="text" class="form-control" name="first_name" value="<?=$user['first_name']??''?>">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Middle name</label>
                                <input type="text" class="form-control" name="middle_name" maxlength="1" value="<?=$user['middle_name']??''?>">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Last name</label>
                                <input type="text" class="form-control" name="last_name" value="<?=$user['last_name']??''?>">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nickname</label>
                    <input type="text" class="form-control" name="nickname"value="<?=$user['nickname']??''?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile number</label>
                    <input type="text" class="form-control" name="mobile" value="<?=$user['mobile']??''?>">
                </div>
                <button type="button" class="btn btn-secondary" onclick="history.back(-1)">Cancel</button>
                <button type="submit" class="btn btn-primary" data-button="submit">Submit</button>
            </form>
</div>

<script>


    const mixin = {
        created() {
            console.log('userData.created!');
        },
        mounted() {
            console.log('userData.mounted!');
            this.$data.userData = {}
        },
        data() {
            return {
                userData: {},
            }
        },
        methods: {
            onUserEdiFormSubmit(event) {
              const form = serializeFormEvent(event);
              console.log(form);
              console.log(this.userData);
                request('admin.userProfileUpdate', form, function(res) {
                    console.log(res);
                    alert('update success');
                }, this.error);
            },
        }
    }



</script>