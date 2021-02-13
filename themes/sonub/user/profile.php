<div class="box border-radius-md">

    <h1>회원 정보</h1>
    <hr>

    <div class="d-flex justify-content-center mt-5">
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


    <form @submit.prevent="onProfileUpdateFormSubmit">

        <div class="form-group mt-5 mb-3">
            <label for="profile_form_email" class="form-label">이메일 주소</label>
            <input class="form-control" type="email" name="email" placeholder="메일 주소를 입력해주세요." v-model="profile.email">
        </div>

        <div class="form-group mb-3">
            <label for="name">First name</label>
            <input type="text" class="form-control" id="name" name="name" v-model="profile.name">
        </div>

        <button type="submit" class="btn btn-primary">저장</button>
    </form>
</div>


<script>
    later(function () {
        app.loadProfileUpdateForm();
    });
</script>