<?php
$a_tag_begin = null;
if ( isset($dwo['post_ID']) ) {
    $_post = post_response($dwo['post_ID']);
    if ( api_error($_post) == false ) {
        $a_tag_begin = "<a href='$_post[url]'>";
        echo $a_tag_begin;
    }
}

?>
<?php if ( isset($dwo['bannerImageUrl']) ) { ?>
    <img class="w-100 mb-1" src="<?=$dwo['bannerImageUrl'] ?? ''?>">
<?php
    }
    if ( $a_tag_begin ) echo "</a>";
?>

