<?php if ( isset($dwo['bannerImageUrl']) ) { ?>
    <img class="w-100 mb-1" src="<?=$dwo['bannerImageUrl'] ?? ''?>">
<?php } ?>
<input type="hidden" name="bannerImageUrl" id="bannerImageUrl" value="<?=$dwo['bannerImageUrl']?>">
<div><input type="file" onchange="onChangeBannerImage(this)"></div>
<script>
    function onChangeBannerImage($this) {
        const $file = $this.files[0];
        fileUpload(
            $this.files[0],
            {
                onUploadProgress: function (progressEvent) {
                    app.uploadPercentage = Math.round(
                        (progressEvent.loaded * 100) / progressEvent.total
                    );
                },
            },
            function(success) {
                console.log("success: res.url: ", success.url);
                document.getElementById("bannerImageUrl").value =  success.url;
            },
            function(error) {
                console.log(error);
                alert(error);
            }
        );
    }
</script>