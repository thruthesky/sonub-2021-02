<?php
$user = profile(in('ID'));
?>

<hr>
Admin Menu:
<a href="/?page=admin/user-list">User list</a> |
<a href="/?page=admin/forum-categories">Forum Categories</a> |
<a href="/?page=admin/send-push-notification">Send Push Notifications</a> |
Files |
<a href="/?page=admin/translations">Translations</a>

<hr>

<?php

print_r($user);

?>


<div id="register-page" class="container py-3">
            <h1>User Update</h1>
            <form class="register" onsubmit="return onUserEdiFormSubmit(this)">
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
                    <input type="text" class="form-control" name="nickname"  value="<?=$user['nickname']??''?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile number</label>
                    <input type="text" class="form-control" name="mobile"  value="<?=$user['mobile']??''?>">
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
            onUserEdiFormSubmit() {
                request('user.update', this.userData, function(res) {
                    console.log(res);
                    alert('update success');
                }, this.error);
            },
        }
    }

//    function onUserEdiFormSubmit(form) {
//        const data = objectifyForm(form);
//        data['route'] = 'user.update';
//        const src = $profile_photo.find('img').attr('src');
//
//        if (src !== "<?//=ANONYMOUS_PROFILE_PHOTO?>//") {
//            data['photo_url'] = src;
//        }
//
//        $.ajax( {
//            method: 'GET',
//            url: apiUrl,
//            data: data
//        }).done(function(re) {
//            if ( isBackendError(re) ) {
//                alert(re);
//            }
//            else {
//                alertModal(tr('appName'), 'Profile update success!');
//            }
//        })
//            .fail(function() {
//                alert( "Server error" );
//            });
//        return false;
//    }


</script>