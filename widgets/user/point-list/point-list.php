<?php


$user = profile(in('user_ID'));



$rows = get_point_history([ 'to_user_ID' => $user['ID']  ]);

?>
<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">사유</th>
        <th scope="col">포인트 증/감</th>
        <th scope="col">날짜</th>
    </tr>
    </thead>
    <tbody>
    <? foreach($rows as $row) { ?>
        <tr>
            <th scope="row"><?=$row['ID']?></th>
            <td><?=$row[REASON]?></td>
            <td><?=number_format($row['to_user_point_apply'])?></td>
            <td><?=date('Y-m-d H:i:s', $row['stamp'])?></td>
        </tr>
    <? } ?>
    </tbody>
</table>
