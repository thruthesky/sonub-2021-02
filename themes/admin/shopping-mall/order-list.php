<?php
/**
 * @file order-list.php
 */

global $wpdb;
if ( in('mode') == 'delete' ) {
    $wpdb->query("TRUNCATE api_order_history");
} else if ( in('mode') == 'delete-item' ) {
    api_item_order_point_restore(in('id'));
    $wpdb->query("DELETE FROM api_order_history WHERE ID=" . in('id'));
} else if ( in('mode') == 'order-confirm' ) {
    $order = $wpdb->get_row("SELECT * FROM api_order_history WHERE ID=" . in('id'), ARRAY_A);
    if ( $order['confirmed'] ) {
        jsBack('이미 구매확정되었습니다.');
    }
    $info = decode_order_info($order);
    $wpdb->update('api_order_history', ['confirmed' => time()], ['ID' => in('id')]);
    $applied = add_user_point($order['user_ID'], $info['pointToSave']);
    add_point_history(POINT_ORDER_CONFIRM, 0, $applied, $order['ID'], 0);
}
$rows = $wpdb->get_results("SELECT * FROM api_order_history ORDER BY stamp DESC", ARRAY_A);
?>
<h1>주문관리</h1>
<div class="d-flex justify-content-end">
    <a class="btn btn-danger" href="/?page=admin.shopping-mall.order-list&mode=delete" onclick="return confirm('경고: 모든 주문 데이터가 삭제됩니다. 이것은 오직 개발자만 할 수 있는 명령입니다. 관리자는 이 버튼(메뉴) 자체를 보면 안됩니다. 이 버튼이 보이면, 개발자에게 얘기해주세요.');">전체 주문 삭제</a>
</div>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">회원</th>
        <th scope="col">결제 금액</th>
        <th scope="col">주문 상품</th>
    </tr>
    </thead>
    <tbody>
    <? foreach($rows as $row ) {
        $user = profile($row['user_ID']);
        $info = decode_order_info($row);
        ?>
        <tr>
            <th scope="row"><?=$row['ID']?></th>
            <td><?=$user['name']??$user['phoneNo']??$user['user_email']?>

            </td>
            <td nowrap>
                <?=number_format($info['paymentAmount'])?>

                <? if ( $row['confirmed'] ) { ?>
                    <div>구매확정됨</div>
                <? } else { ?>
                    <a class="d-block mb-1 btn btn-warning btn-sm" href="/?page=admin.shopping-mall.order-list&mode=delete-item&id=<?=$row['ID']?>" onclick="return confirm('주문을 삭제하시겠습니까?');">주문삭제</a>
                    <a class="btn btn-info btn-sm" href="/?page=admin.shopping-mall.order-list&mode=order-confirm&id=<?=$row['ID']?>" onclick="return confirm('환불이 되지 않는 단계인 구매 확정을 하고 회원에게 적립금을 지급하시겠습니까?');">구매확정</a>
                <? } ?>
            </td>
            <td>
                <?
                foreach( $info['items'] as $item ) {
                    ?>
                    <div class="bg-lighter p-3  border-radius-md mb-2">
                        <div>번호: <?=$item['postId']?></div>
                        <div>제목: <?=$item['postTitle']?></div>
                        <? if ( $item['optionItemPrice'] ) { ?>
                            <? foreach( $item['selectedOptions'] as $name => $option ) { ?>
                                <div class="bg-secondary mb-2 p-2 white border-radius-md">
                                    가격: <?=$name?>
                                    <?
                                    list($name, $price) = explode('=', $name);
                                    $discounted_price = $price - $price * $option['discountRate'] / 100;
                                    ?>
                                    (할인된 가격: <?=number_format($discounted_price)?>)
                                    <br>
                                    <div class="em">개수: <?=$option['count']?></div>
                                    할인: <?=$option['discountRate']?>%<br>
                                    소계: <?=number_format( round(($option['price'] - $option['price'] * $option['discountRate'] / 100 ) * $option['count']) )?>
                                </div>
                            <? } ?>
                        <? } else {
                            $default_option = $item['selectedOptions']['Default Option'];
                            unset($item['selectedOptions']['Default Option']);
                            ?>
                            <div class="bg-secondary mb-2 p-2 white border-radius-md">
                                금액: <?=number_format($item['price'])?> (할인된 금액: <?=number_format($item['price'] - $item['price'] * $item['discountRate'] / 100)?> )<br>
                                <div class="em">개수: <?=$default_option['count']?></div>
                                할인: <?=$item['discountRate']?>%<br>

                                <? foreach($item['selectedOptions'] as $name => $option) { ?>
                                    옵션: <?=$name?><br>
                                <? } ?>
                            </div>
                        <? } ?>
                        <div>소계: <?=number_format($item['orderPrice'])?></div>
                    </div>
                    <?
                }
                ?>

                <div class="mb-2">배송비: <?=number_format($info['deliveryFeePrice'])?></div>
                <div class="mb-2">사용된 회원 포인트: <?=number_format($info['pointToUse'])?></div>
                <div class="mb-2">추가될 적립금: <?=number_format($info['pointToSave'])?></div>

                <div class="bg-lighter p-3 border-radius-md">
                    배송정보<br>
                    이름: <?=$info['name']?><br>
                    주소: <?=$info['address1']?> <?=$info['address2']?><br>
                    전화번호: <?=$info['phoneNo']?><br>
                    메모: <?=$info['memo']?><br>
                </div>

            </td>
        </tr>
    <? } ?>

    </tbody>
</table>

<style>
    .em {
        font-weight: bold;
        color: orange;
    }
</style>

<ul>
    <li>총 결제 금액은 배송비와 회원의 포인트가 포함된 것입니다.</li>
    <li>'개수'는 사용자가 주문한 물품의 개 수 있습니다. 이 개수를 배송해야합니다.</li>
</ul>