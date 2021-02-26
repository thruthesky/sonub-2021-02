<?php

?>

<h1>사용자 목록</h1>

<?php
$no_of_records_per_page = 20;
$page_no = in('page_no', 1);
if ( in('page_no', 1) < 1 ) $page_no = 1;

if ( in('keyword') ) {
    $q = new WP_User_Query( [
        'paged' => $page_no,
        'number' => $no_of_records_per_page,
        'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'name',
                    'value' => in('keyword'),
                    'compare' => 'LIKE'
                ],
            [
                'key' => 'phoneNo',
                'value' => in('keyword'),
                'compare' => 'LIKE'
            ],
            [
                'key' => 'email',
                'value' => in('keyword'),
                'compare' => 'LIKE'
            ],
            ],
        ]);
    $users = $q->get_results();
    $total = $q->get_total();
} else {
    $users = get_users( [ 'fields' => 'all_with_meta', 'number' => $no_of_records_per_page, 'paged' => $page_no] );
    $total =count_users()['total_users'];
}
?>

<form>
    <input type="hidden" name="page" value="admin.user.list">
    <div class="input-group">
        <input type="text" class="form-control" name="keyword">
        <button class="input-group-text btn btn-primary btn-sm" type="submit">검색</button>
    </div>
</form>
<table class="table table-hover">
    <thead>
    <th class="">번호</th>
    <th class="">이름</th>
    <th class="">전화번호</th>
    <th class="">포인트</th>
    <th class="">메일</th>
    <th class="">버튼</th>
    </thead>


    <tbody>
    <?php
    foreach($users as $user){
        $ln = "?page=admin/user/edit&user_ID=" . $user->ID;
        $u = profile($user->ID);
        $u['email'] = $u['email'] ?? '';
        $u['name'] = $u['name'] ?? '';
        $u['phoneNo'] = $u['phoneNo'] ?? '';
        $u['point'] = $u['point'] ?? '0';
        echo <<<EOH
<a href="$ln">
    <tr class="">
        <td class="">{$user->ID}</td>
        <td class="">{$u['name']}</td> 
        <td class="">{$u['phoneNo']}</td>
        <td class=""><a href="/?page=admin.user.point-history&user_ID={$u['ID']}">{$u['point']} <i class="fa fa-external-link-square-alt"></i></a></td>
        <td class="">{$u['email']}</td>
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
    'total_no_of_posts' => $total,
    'no_of_posts_per_page' => $no_of_records_per_page,
    'url' => '/?page=admin/user/list&page_no={page_no}&keyword=' . in('keyword'),
]);


?>
