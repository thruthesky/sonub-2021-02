<?php

?>

<h1>사용자 검색</h1>

<?php
$no_of_records_per_page = 20;
$page_no = in('page_no', 1);
if ( in('page_no', 1) < 1 ) $page_no = 1;

$users = get_users( [ 'fields' => 'all_with_meta', 'number' => $no_of_records_per_page, 'paged' => $page_no] );
?>

<table class="table table-hover">
    <thead>
    <th class="">번호</th>
    <th class="">이름</th>
    <th class="">메일</th>
    <th class="">버튼</th>
    </thead>


    <tbody>
<?php
foreach($users as $user){
    $ln = "?page=admin/user/edit&user_ID=" . $user->ID;
    echo <<<EOH
<a href="$ln">
    <tr class="">
        <td class="">{$user->ID}</td>
        <td class="">{$user->nickname}</td> 
        <td class="">{$user->user_email}</td>
        <td class=""><a href="/?page=admin/user/edit&user_ID={$user->ID}">Edit</a></td>
    </tr>
</a>
EOH;


}
?>
    </tbody>
</table>
<?php


include_once widget('pagination/pagination-default', [
    'page_no' => $page_no,
//    'blocks' => 5,
    'arrow' => true,
    'total_no_of_posts' => count_users()['total_users'],
    'no_of_posts_per_page' => $no_of_records_per_page,
    'url' => '/?page=admin/user/list&page_no={page_no}'
]);


?>
