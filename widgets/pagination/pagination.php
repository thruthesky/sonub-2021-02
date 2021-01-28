<?php

$options = get_widget_options();

$page_no = $options['page_no'] ?? 1;
$blocks = $options['blocks'] ?? 7;
$arrows = $options['arrows'] ?? false;


if ( !isset($options['total_rows']) || empty($options['total_rows']) ) {
//    return jsAlert('total_rows is empty.');
    return;
}

if ( !isset($options['url']) || empty($options['url']) ) {
    return jsAlert('url is empty.');
}


$no_of_records_per_page = $options['no_of_records_per_page'] ?? 10;
$offset = ($page_no-1) * $no_of_records_per_page;
$total_no_of_pages = ceil($options['total_rows'] / $no_of_records_per_page);
$second_last = $total_no_of_pages - 1;
$previous_page = $page_no - $blocks;
$next_page = $page_no + $blocks;



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
            echo "<li  class='page-item'><a class='page-link' href='". _url(1) ."'>" . ($arrows ? '&lsaquo;&lsaquo;' :'First') . "</a></li>";
        }
        ?>

        <li <?php if($page_no <= 1){ echo "class='page-item disabled'"; } ?>>
            <a class="page-link" <?php if($page_no > 1){ echo "href='". _url($previous_page) ."'"; } ?>><?=$arrows ? '&lsaquo;' :'Previous'?></a>
        </li>

        <?php
        if ($total_no_of_pages <= 10){
            for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
                if ($counter == $page_no) {
                    echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                }else{
                    echo "<li class='page-item'><a class='page-link' href='". _url($counter) ."'>$counter</a></li>";
                }
            }
        }
        elseif($total_no_of_pages > 10){

            $until = $page_no + $blocks;
            if ( $until > $total_no_of_pages ) $until = $total_no_of_pages + 1;


                for ($counter = $page_no; $counter < $until; $counter++){
                    if ($counter == $page_no) {
                        echo "<li class='page-item active'><a class='page-link'>$counter</a></li>";
                    }else{
                        echo "<!----- ----->";
                        echo "<li  class='page-item'><a class='page-link' href='". _url($counter) ."'>$counter</a></li>";
                    }
                }

        }
        ?>

        <?php if($page_no + $blocks <= $total_no_of_pages){?>

        <li  class='page-item' <?php if($page_no >= $total_no_of_pages){ echo "class='disabled'"; } ?>>
            <a class='page-link' <?php if($page_no < $total_no_of_pages) { echo "href='". _url($next_page) ."'"; } ?>><?=$arrows ? '&rsaquo;' :'Next'?></a>
        </li>


            <li class='page-item'><a class='page-link' href='<?=_url($total_no_of_pages)?>'><?=$arrows ? '&rsaquo;&rsaquo;' :'Last'?></a></li>
        <?php } ?>
    </ul>
</nav>