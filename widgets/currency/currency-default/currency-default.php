<?php
/**
 * @file currency-default
 */
/**
 * 현재 카페 국가가 KR 이면, KRW 과 USD 만 표시한다.
 * 그 외에는 KRW, USD 를 같이 표시한다.
 *
 * 예를 들어 필리핀 PHP 의 경우,
 * 1 PHP 에 원화 얼마.
 * 1 USD 에 PHP 에 얼마.
 * 1 USD 에 원화 얼마.
 *
 * 만약, 현재 국가가 한국이라면, 환율 정보는 아래와 같이 저장된다.
[KRW_KRW] => 1
[KRW_USD] => 0.000904
[USD_KRW] => 1106.03993
 * 즉, 1원은 1원이고, 1 달러 당 원에 대한 가치만 올바른 정보이다. 즉, 1달러당 원화 정보만 보여준다.
 *
 * 보여줄 값이 1 또는 0.00 이하인 경우에는 표시하지 않는다. 예를 들어, 1 원이 0.000904 달러인데, 너무 적으므로 보여주지 않는다.
 *
 *
 */


$cafe_code =  country_currency_code(cafe_country_code());
$krw = country_currency_code('KR') ;
$usd = country_currency_code('US') ;

$currencies = get_cache($cafe_code);
if ( ! $currencies ) {



    $key = 'bd6ed497a84496be7ee9';
    $url = "https://free.currconv.com/api/v7/convert?q={$cafe_code}_KRW,{$cafe_code}_USD&compact=ultra&apiKey=$key";
    $re = file_get_contents($url);
    $currencies = json_decode($re, true);
    $url = "https://free.currconv.com/api/v7/convert?q=USD_KRW,USD_{$cafe_code}&compact=ultra&apiKey=$key";
    $re = file_get_contents($url);
    $currencies = array_merge($currencies, json_decode($re, true));




    $re = set_cache("$cafe_code", $currencies, 60);
    if ( $re ) {
        d("알림: 새로운 환율 데이터 저장 성공");
    }
}

$letters = country_currency_korean_letter();

foreach( $currencies as $names => $rate ) {
    if ( $rate == 1 ) continue;
    if ( $rate < 0.009 ) continue;
    $arr = explode('_', $names);

    if ( $rate < 100 ) $rate = round($rate, 3);
else    $rate = round($rate, 2);

    echo "<div>";
    echo "1 ".$letters[$arr[0]]['Code'].": $rate " . $letters[$arr[1]]['Code'];
    echo "</div>";
}
d($currencies);






?>
<div class="box border-radius-md mb-2 p-3">

</div>
