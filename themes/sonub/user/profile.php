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

    <form @submit.prevent="onProfileFormSubmit">

        <? if ( empty(my('name')) ) { ?>
            <div class="form-group mt-5 mb-3">
                <label for="name">이름</label>
                <input type="text" class="form-control" placeholder="이름" v-model="profile.name">
                <div class="form-text">
                    주의: 이름은 딱 한번만 입력 할 수 있습니다. 반드시 본명을 입력해주세요.
                </div>
            </div>
        <? } else { ?>
            <div class="mt-5 mb-3">
                <div>이름</div>
                <div class="fs-lg">
                    <?=my('name')?>
                </div>
            </div>
        <? } ?>
        <div class="form-group mb-3">
            <label for="profile_form_email" class="form-label">이메일 주소</label>
            <input class="form-control" type="email" placeholder="메일 주소를 입력해주세요." v-model="profile.email">
        </div>

        <div class="form-group mb-3">
            <label for="name">좌우명</label>
            <input type="text" class="form-control" placeholder="좌우명을 입력하세요." v-model="profile.motto">
        </div>
        <div class="form-group mb-3">
            <label for="name">나의 포인트</label>
            <?=number_format(my('point'))?>
        </div>
        <button type="submit" class="btn btn-primary">저장</button>
    </form>
</div>
<script>
    later(function () {
        app.loadProfile();
    });
    const mixin = {
        methods: {
            onProfileFormSubmit() {
                const data = {
                    email: this.profile.email,
                    motto: this.profile.motto
                };
                <? if ( empty(my('name')) ) { ?>
                data['name'] = this.profile.name;
                <? } ?>
                this.userProfileUpdate(data, function(profile) {
                    console.log('success: ', profile);
                    alert("프로필 정보를 수정하였습니다.");
                });
            }
        }
    }
</script>