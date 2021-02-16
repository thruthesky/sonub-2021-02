<?php

?>


<h1><?=ln('FORUM CATEGORIES', '게시판 관리')?></h1>

<?php



$categories = get_category_tree();


?>
<div class="box mt-3 border-radius-md">
    <form method="get">
        <input type="hidden" name="page" value="admin/forum/list.submit">
        <label>게시판 생성</label>
        <div class="row">
            <div class="col">
                <input class="form-control" type="text" name="cat_name" value="" placeholder="게시판 아이디 입력">
            </div>
            <div class="col">
                <button class="btn btn-primary" type="submit"><?=ln('Create Category', '게시판 생성')?></button>
            </div>

            <div class="hint">
                게시판 카테고리는 게시판 아이디와 같습니다.
            </div>
        </div>
    </form>
</div>
<?php


?>

<section class="pt-5">
    <table class="table table-striped">
        <thead>
        <tr>
            <th nowrap scope="col"><?=ln('No.</br>Posts', '글수')?></th>
            <th scope="col"><?=ln('Category ID', '게시판 ID')?></th>
            <th scope="col"><?=ln('Title', '게시판 이름')?></th>
            <th scope="col"><?=ln('Description', '설명')?></th>
            <th scope="col" class="text-center">View<br>List</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($categories as $category) {
            $category->list_on_view = get_term_meta($category->cat_ID, 'list_on_view', true);
            //    print_r($category);
            ?>
            <tr>
                <td><?php echo $category->count ?></td>
                <td nowrap><a href="?page=admin.forum.list&slug=<?php echo $category->slug ?>"><?php

                        if ( $category->parent ) {
                            echo str_repeat(' <b class="light">--</b> ', $category->depth );
                        }
                        echo $category->slug;
                        ?>
                    </a></td>
                <td><?php
                    echo $category->name
                    ?></td>
                <td>
                    <a href="/?page=forum.list&category=<?=$category->slug?>">
                        <i class="fa fa-link fs-sm"></i>
                        <?php echo $category->description ?>
                    </a>
                </td>
                <td class="text-center"> <i class="fa fa-<?=$category->list_on_view ? 'check green' : 'times red'?>"></i></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</section>


<ul>
    <li>게시판 ID 를 클릭하면, 게시판 설정을 변경 할 수 있습니다.</li>
    <li>
        View List - 글 읽기 페이지 아래에, 글 목록을 보여주는 옵션입니다.
    </li>
</ul>