<h1>Profile page</h1>


<div class="d-flex justify-content-center">
    <div>
        <div class="position-relative size-100 of-hidden">
            <div class="position-relative d-center size-100 photo-background circle">
                <img class="size-100 circle" :src="profile.profile_photo_url" v-if="profile.profile_photo_url">
                <i class="fa fa-user fs-xxl" v-if="!profile.profile_photo_url"></i>
                <i class="fa fa-camera position-absolute bottom left p-2 fs-lg red"></i>
            </div>
            <input class="position-absolute cover fs-xxl opacity-0" type="file" @change="onProfilePhotoUpload($event)">
        </div>
        <div class="progress mt-3 w-100px" style="height: 5px;" v-if="uploadPercentage > 0">
            <div class="progress-bar" role="progressbar" :style="{width: uploadPercentage + '%'}" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
<div>Email address</div>
<div>{{ profile.user_email }}</div>



<form @submit.prevent="onProfileUpdateFormSubmit">

    <div class="form-group">
        <label for="first_name">First name</label>
        <input type="text" class="form-control" id="first_name" name="first_name" v-model="profile.first_name">
    </div>

    <div class="form-group">
        <label for="last_name">Last name</label>
        <input type="text" class="form-control" id="last_name" name="last_name" v-model="profile.last_name">
    </div>



    <div class="form-group">
        <label for="profile_nickname">Nickname</label>
        <input type="text" class="form-control" id="profile_nickname" name="nickname" v-model="profile.nickname">
    </div>


    <div class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="male" value="M" v-model="profile.gender">
            <label class="form-check-label" for="male">Male,</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="gender" id="female" value="F" v-model="profile.gender">
            <label class="form-check-label" for="female">Female</label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<script>
later(function () {
   app.loadProfileUpdateForm();
});
</script>