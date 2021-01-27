const fileUploadButton = {
  data() {
    return {
      count: 0,
    };
  },
  template: `
  <div style="overflow: hidden;" class="position-relative mr-2">
      <input style="opacity: 0" class="position-absolute" type="file" name="file" @change="onFileChange($event)" />
      <i class="fa fa-camera fs-xl"></i>
  </div>`,
};
