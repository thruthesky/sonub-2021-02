<?php
/**
 * Filter 404 response code to 200.
 * @return bool
 */
function wpd_do_stuff_on_404(){
    if( is_404() ){
        global $wp_query;
        status_header( 200 );
        $wp_query->is_404=false;
        return false;
    }
}
add_action( 'template_redirect', 'wpd_do_stuff_on_404' );

function remove_redirect_guess_404_permalink( $redirect_url ) {
    if ( is_404() )
        return false;
    return $redirect_url;
}
add_filter( 'redirect_canonical', 'remove_redirect_guess_404_permalink' );

function live_reload_js() {
    /// TODO print this only for localhost(local dev)
    echo <<<EOH
   <script src="https://local.nalia.kr:12345/socket.io/socket.io.js"></script>
   <script>
       var socket = io('https://local.nalia.kr:12345');
       socket.on('reload', function (data) {
           console.log(data);
           // window.location.reload(true);
           location.reload();
       });
   </script>
EOH;
}

