function build_query(params) {
  const esc = encodeURIComponent;
  return Object.keys(params)
    .map(function (k) {
      return esc(k) + "=" + esc(params[k]);
    })
    .join("&");
}


/**
 * Add login user's session id into 'data'
 * @param data
 */
function addSessionId(data) {
    const _user = getLocalStorage('user');
    if ( _user && typeof _user['session_id'] !== 'undefined' ) {
        data['session_id'] = _user['session_id'];
    }
}

function requestResult(res, successCallback, errorCallback) {
    if ( res.data.code !== 0 ) {
        if ( typeof errorCallback === 'function' ) {
            errorCallback(res.data.code);
        }
    } else {
        if ( typeof successCallback === 'function') {
            successCallback(res.data.data);
        }
    }
}
function request(route, data, successCallback, errorCallback) {
    data = Object.assign({}, data, {route: route});

    addSessionId(data);
    console.log('URL:', config.apiUrl + '?' + build_query(data));
    axios.post(config.apiUrl, data).then(function (res) {
        requestResult(res, successCallback, errorCallback);
    }).catch(errorCallback);
}

function fileUpload(file, options, successCallback, errorCallback) {
    const form = new FormData();
    form.append('route', 'file.upload');
    form.append('session_id', app.sessionId());
    form.append('userfile', file);
    axios.post(config.apiUrl, form, options)
        .then(function (res) {
            requestResult(res, successCallback, errorCallback);
        })
        .catch(errorCallback);
}



function move(uri) {
  location.href = uri;
}
function refresh() {
  location.reload();
}






function setLocalStorage(name, value) {
    value = JSON.stringify(value);
    localStorage.setItem(name, value);
}

function getLocalStorage(name) {
    const val = localStorage.getItem(name);
    if ( val ) {
        return JSON.parse(val);
    } else {
        return val;
    }
}


/**
 * Serialize an class object. So, the class object can be used as an array object.
 *
 * @usage Use this to serialize FormData object.
 * @param data
 * @returns {{}}
 */
function serialize (data) {
    let obj = {};
    for (let [key, value] of data) {
        obj[key] = value;
    }
    return obj;
}

/**
 * Get data from form event.
 *
 * @param event
 * @returns {{}}
 */
function serializeFormEvent(event) {
    return serialize(new FormData(event.target));
}