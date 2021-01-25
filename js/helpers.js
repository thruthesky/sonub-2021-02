function build_query(params) {
    const esc = encodeURIComponent;
    return Object.keys(params)
        .map(function(k) {return esc(k) + '=' + esc(params[k]);})
        .join('&');
}
function request(route, data, successCallback, errorCallback) {
    data = Object.assign({}, data, {route: route});

    if (app.loggedIn()) {
        data['session_id'] = app.sessionId();
    }
    console.log('URL:', config.apiUrl + '?' + build_query(data));
    axios.post(config.apiUrl, data).then(function (res) {
        if ( res.data.code !== 0 ) {
            if ( typeof errorCallback === 'function' ) {
                errorCallback(res.data.code);
            }
        } else {
            successCallback(res.data.data);
        }
    }).catch(errorCallback);
}

function move(uri) {
    location.href = uri;
}
function refresh() {
    location.reload();
}
