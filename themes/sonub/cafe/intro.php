<?php
$setting = get_cafe_domain_settings();
?>
<section class="mb-3 border-radius-md bg-secondary white p-3 fs-xs">
    전 세계 교민 포털과 함께하는 <?=$setting['siteName']?>는
    <?=$setting['countryName']?> 국가를 담당하는 교민 포털 사이트로서
    교민 카페 운영을 위한 최고의 기능을 가지고 있습니다.
</section>


<section class="box border-radius-md markdown">


    <?php
    $_html = <<<EOH


목차
[TOC]


# 본인 인증

많은 온라인 카페(커뮤니티)에서 익명성을 악용하여 온갖 욕설과 비방으로 문을 받은 곳이 한 곳이 아닙니다.

* {$setting['siteName']}에서는 

* a
* a
* a
* a
* a
* a



# 푸시 알림

많은 온라인 카페(커뮤니티)에서 익명성을 악용하여 온갖 욕설과 비방으로 문을 받은 곳이 한 곳이 아닙니다.

* {$setting['siteName']}에서는 


* a
* a
* a
* a
* a
* a



# 설치 가능

많은 온라인 카페(커뮤니티)에서 익명성을 악용하여 온갖 욕설과 비방으로 문을 받은 곳이 한 곳이 아닙니다.

* {$setting['siteName']}에서는 


* a
* a
* a
* a
* a

* a
* a
* a
* a
* a
* a

* a
* a
* a
* a
* a
* a

* a


# 공유 카페

* {$setting['siteName']}에서는 {$setting['countryName']} 관련 카페 운영을 위한
 무료 카페 서비스를 하고 있으며, 각 카테고리 별로 모든 글이 공유됩니다.
 
* 공유를 원하지 않는 경우, 별도의 게시판 카테고리를 만들면 됩니다.


* a
* a
* a
* a
* a
* a
* a
* a

* a
* a
* a
* a
* a
* a

* a
* a
* a
* a
* a
* a

* a
* a
* a
* a


EOH;
    include THEME_DIR . '/etc/markdown/display-markdown.php';
    ?>
</section>

