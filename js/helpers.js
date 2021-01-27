function build_query(params) {
  const esc = encodeURIComponent;
  return Object.keys(params)
    .map(function (k) {
      return esc(k) + "=" + esc(params[k]);
    })
    .join("&");
}
function request(route, data, successCallback, errorCallback) {
  data = Object.assign({}, data, { route: route });

  if (app.loggedIn()) {
    data["session_id"] = app.sessionId();
  }
  console.log("URL:", config.apiUrl + "?" + build_query(data));
  axios
    .post(config.apiUrl, data)
    .then(function (res) {
      if (res.data.code !== 0) {
        if (typeof errorCallback === "function") {
          errorCallback(res.data.code);
        }
      } else {
        successCallback(res.data.data);
      }
    })
    .catch(errorCallback);
}

/**
 * 
 * @param {File} file 
 * @param {function} uploadProgress 
 * @param {function} successCallback 
 * @param {function} errorCallback 
 */
function fileUpload(file, uploadProgress, successCallback, errorCallback) {
  if (app.notLoggedIn()) return errorCallback("Login first!");

  const formData = new FormData();

  formData.append("userfile", file);
  formData.append("session_id", app.sessionId());
  formData.append("route", "file.upload");

  const apiUrl = window.location.origin + "/wp-content/themes/withcenter-backend-v3/api/index.php";

  axios
    .post(apiUrl, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
      onUploadProgress: function (progEvent) {
        const progress = Math.round((100 * progEvent.loaded) / progEvent.total);
        uploadProgress(progress);
      },
      timeout: 60 * 1000 * 10, /// 10 minutes.
    })
    .then(function (res) {
      if (res.data.code !== 0) {
        if (typeof errorCallback === "function") {
          errorCallback(res.data.code);
        }
      } else {
        successCallback(res.data.data);
      }
    })
    .catch(errorCallback);
}

function move(uri) {
  location.href = uri;
}
function refresh() {
  location.reload();
}
