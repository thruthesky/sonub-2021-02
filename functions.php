<?php

function live_reload_js() {
    /// TODO print this only for localhost(local dev)
    echo <<<EOH
   <script src="http://local.nalia.kr:12345/socket.io/socket.io.js"></script>
   <script>
       var socket = io('http://local.nalia.kr:12345');
       socket.on('reload', function (data) {
           console.log(data);
           // window.location.reload(true);
           location.reload();
       });
   </script>
EOH;
}

