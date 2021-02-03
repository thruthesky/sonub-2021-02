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
    const _sid = app.sessionId();
    if ( _sid ) {
        data['session_id'] = _sid;
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
function getFormData(event) {
    return serializeFormEvent(event);
}


/*! js-cookie v3.0.0-rc.1 | MIT */
!function(e,t){"object"==typeof exports&&"undefined"!=typeof module?module.exports=t():"function"==typeof define&&define.amd?define(t):(e=e||self,function(){var n=e.Cookies,r=e.Cookies=t();r.noConflict=function(){return e.Cookies=n,r}}())}(this,function(){"use strict";function e(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)e[r]=n[r]}return e}var t={read:function(e){return e.replace(/(%[\dA-F]{2})+/gi,decodeURIComponent)},write:function(e){return encodeURIComponent(e).replace(/%(2[346BF]|3[AC-F]|40|5[BDE]|60|7[BCD])/g,decodeURIComponent)}};return function n(r,o){function i(t,n,i){if("undefined"!=typeof document){"number"==typeof(i=e({},o,i)).expires&&(i.expires=new Date(Date.now()+864e5*i.expires)),i.expires&&(i.expires=i.expires.toUTCString()),t=encodeURIComponent(t).replace(/%(2[346B]|5E|60|7C)/g,decodeURIComponent).replace(/[()]/g,escape),n=r.write(n,t);var c="";for(var u in i)i[u]&&(c+="; "+u,!0!==i[u]&&(c+="="+i[u].split(";")[0]));return document.cookie=t+"="+n+c}}return Object.create({set:i,get:function(e){if("undefined"!=typeof document&&(!arguments.length||e)){for(var n=document.cookie?document.cookie.split("; "):[],o={},i=0;i<n.length;i++){var c=n[i].split("="),u=c.slice(1).join("=");'"'===u[0]&&(u=u.slice(1,-1));try{var f=t.read(c[0]);if(o[f]=r.read(u,f),e===f)break}catch(e){}}return e?o[e]:o}},remove:function(t,n){i(t,"",e({},n,{expires:-1}))},withAttributes:function(t){return n(this.converter,e({},this.attributes,t))},withConverter:function(t){return n(e({},this.converter,t),this.attributes)}},{attributes:{value:Object.freeze(o)},converter:{value:Object.freeze(r)}})}(t,{path:"/"})});

