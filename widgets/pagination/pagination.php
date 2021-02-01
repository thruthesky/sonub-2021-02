<?php
/**
 * @file pagination
 */
/**
 * $options['no_of_posts_per_page'] is the no of posts to display in a post list page.
 * $options['page_no'] is the current page no. It begins with no. 1.
 * $options['blocks'] is the no of pages to display on navigation bar(at bottom of the post list page)
 * $options['arrow'] to show or not the quick arrow button for very first page and very last page.
 * $option['total_no_of_posts'] is the total no of posts of the category.
 * $option['url'] is the url for the post list page. In the url, `{page_no}` will be replaced with page no.
 *   - For example, if url is "?page=post.list&category=qna&page_no={page_no}" then, it will be converted into "?page=post.list&category=qna&page_no=5" where '5' is the page_no.
 */


$page_no = $options['page_no'] ?? 1;
$blocks = $options['blocks'] ?? 7;
$arrows = $options['arrows'] ?? false;


if ( !isset($options['total_no_of_posts']) || empty($options['total_no_of_posts']) ) {
//    return jsAlert('total_no_of_posts is empty.');
    return;
}


if ( !isset($options['url']) || empty($options['url']) ) {
    return jsAlert('Url is empty on post list navigation bar');
}


$no_of_posts_per_page = $options['no_of_posts_per_page'] ?? 10;
$offset = ($page_no-1) * $no_of_posts_per_page;
$total_no_of_pages = ceil($options['total_no_of_posts'] / $no_of_posts_per_page);
$second_last = $total_no_of_pages - 1;
$previous_page = $page_no - $blocks;



function _url($no) {
    global $options;
    $re = str_replace('{page_no}', $no, $options['url']);
    return $re;
}


?>

<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
    <strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>
<nav aria-label="Page navigation" style="display: inline-block">
    <ul class="pagination">
        <?php
        if($page_no > 1) {
            echo "<li  class='page-item'><a class='page-link' href='". _url(1) ."'>" . ($arrows ? '&lsaquo;&lsaquo;' : 'First') . "</a></li>";
        }
        ?>

        <li <?php if($page_no <= 1){ echo "class='page-item disabled'"; } ?>>
            <a class="page-link" <?php if($page_no > 1){ echo "href='". _url($previous_page) ."'"; } ?>><?=$arrows ? '&lsaquo;' :'Previous'?></a>
        </li>

        <?php
        if ($total_no_of_pages <= $blocks ){
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                } else{
                    echo "<li class='page-item'><a class='page-link' href='". _url($counter) ."'>$counter</a></li>";
                }
            }
        }
        else if($total_no_of_pages > $blocks){

            $counter_begin = floor(($page_no-1) / $blocks) * $blocks + 1;
            $until = $counter_begin + $blocks;
            if ( $until > $total_no_of_pages ) $until = $total_no_of_pages + 1;

                for ($counter = $counter_begin; $counter < $until; $counter++){
                    if ($counter == $page_no) {
                        echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                    }else{
                        echo "<li  class='page-item'><a class='page-link' href='". _url($counter) ."'>$counter</a></li>";
                    }
                }

        }
        ?>

        <?php if($counter_begin + $blocks <= $total_no_of_pages) {

            $next_page = $counter_begin + $blocks;

            ?>

        <li  class='page-item' <?php if($page_no >= $total_no_of_pages) { /** @todo Is this code working? */ echo "class='disabled'"; } ?>>
            <a class='page-link' <?php if($page_no < $total_no_of_pages) { echo "href='". _url($next_page) ."'"; } ?>><?=$arrows ? '&rsaquo;' :'Next'?></a>
        </li>


            <li class='page-item'><a class='page-link' href='<?=_url($total_no_of_pages)?>'><?=$arrows ? '&rsaquo;&rsaquo;' :'Last'?></a></li>
        <?php } ?>
    </ul>
</nav>