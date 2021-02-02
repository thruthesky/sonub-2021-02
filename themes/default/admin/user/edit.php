<?php
$user = profile(in('ID'));
?>



<?php

d($user);

?>


<div id="register-page" class="container py-3">
            <h1>User Update</h1>
            <form class="register" @submit.prevent="onUserEdiFormSubmit">
                <input type="hidden" name="session_id" :value="userData.session_id">
                <div class="mb-3">
                    <label  class="form-label">Email address</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="user_email" v-model="userData.user_email">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="user_pass"  v-model="userData.user_pass">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label  class="form-label">First name</label>
                                <input type="text" class="form-control" name="first_name" v-model="userData.first_name">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Middle name</label>
                                <input type="text" class="form-control" name="middle_name" maxlength="1" v-model="userData.middle_name">
                            </div>
                        </div>
                        <div class="col">

                            <div class="mb-3">
                                <label class="form-label">Last name</label>
                                <input type="text" class="form-control" name="last_name" v-model="userData.last_name">
                            </div>

                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nickname</label>
                    <input type="text" class="form-control" name="nickname" v-model="userData.nickname">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile number</label>
                    <input type="text" class="form-control" name="mobile" v-model="userData.mobile">
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
            this.$data.userData = {
              user_id: "<?=$user['ID']??''?>",
              user_email: "<?=$user['user_email']??''?>",
              user_pass: "",
              first_name: "<?=$user['first_name']??''?>",
              middle_name: "<?=$user['middle_name']??''?>",
              last_name: "<?=$user['last_name']??''?>",
              nickname: "<?=$user['nickname']??''?>",
              mobile: "<?=$user['mobile']??''?>",
            }
        },
        data() {
            return {
                userData: {},
            }
        },
        methods: {
            onUserEdiFormSubmit() {
              console.log(this.userData);
                request('user.profileUpdate', this.userData, function(res) {
                    console.log(res);
                    alert('update success');
                }, this.error);
            },
        }
    }



</script>