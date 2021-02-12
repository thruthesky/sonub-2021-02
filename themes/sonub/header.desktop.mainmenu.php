<?php
?>
<nav class="desktop-mainmenu mt-3 mb-1">
    <div class="l-center d-flex justify-content-between">

        <? if ( is_in_cafe() ) {
            $co = cafe_option();
            ?>


            <ul class="d-lg-none list-menu bold">
                <? if ( $co['narrow_menu'] ) { ?>
                    <? for( $i = 0; $i < NO_OF_WIDE_CAFE_MENU; $i++ ) {
                        if ( $co["narrow_menu_$i"] ?? false ) { ?>
                            <li>
                                <a class="px-25 py-2 <?= $i == 0 ? 'ps-0' : '' ?>">
                                    <div class="h-1em"><?=get_cafe_category($co["narrow_menu_$i"])['name']?></div>
                                </a>
                            </li>
                        <? } } ?>
                <? } else { ?>
                    <li><a class="px-2 py-2 ps-0" href="/?page=cafe.admin">카페 설정</a></li>
                <? } ?>
            </ul>


            <ul class="d-none d-lg-flex list-menu bold">
                <? if ( $co['wide_menu'] ) { ?>
                    <? for( $i = 0; $i < NO_OF_WIDE_CAFE_MENU; $i++ ) {
                        if ( $co["wide_menu_$i"] ?? false ) { ?>
                            <li>
                                <a href="<?=cafe_url($co["wide_menu_$i"])?>" class="px-2 py-2 <?= $i == 0 ? 'ps-0' : '' ?>">
                                    <div class="h-1em"><?=get_cafe_category($co["wide_menu_$i"])['name']?></div>
                                </a>
                            </li>
                        <? } } ?>
                <? } else { ?>
                    <li><a class="px-2 py-2 ps-0" href="/?page=cafe.admin">카페 설정</a></li>
                <? } ?>
            </ul>


            <ul class="list-menu">
                <li><a class="px-2 py-2 pe-0" href="/?page=cafe/intro"><i class="fa fa-bars fs-lg"></i></a></li>
            </ul>



        <? } else { ?>

            <ul class="list-menu bold">
                <li><a class="px-3 py-2 ps-0" href="/?page=cafe/intro">카페</a></li>
                <li><a class="px-3 py-2" href="<?=cafe_url('travel')?>">여행</a></li>
                <li><a class="px-3 py-2" href="<?=cafe_url('company_book')?>">업소록</a></li>
                <li><a class="px-3 py-2" href="<?=cafe_url('buyandsell')?>">회원장터</a></li>
                <li><a class="px-3 py-2" href="<?=cafe_url('business')?>">비즈니스</a></li>
                <li><a class="px-3 py-2" href="<?=cafe_url('p_c_o_r_s')?>">나라별 교민사이트</a></li>
            </ul>
            <ul class="list-menu">
                <li><a class="px-3 py-2 ps-0" href="/?page=cafe/intro"><i class="fa fa-question-circle fs-lg"></i></a></li>
                <li><a class="px-3 py-2 ps-0" href="/?page=cafe/intro"><i class="fa fa-bars fs-lg"></i></a></li>
            </ul>
        <? } ?>

    </div>
</nav>