<?php

require_once(V3_DIR . '/v3-load.php');
require_once(V3_DIR . '/routes/forum.route.php');




?>
<h1>Forum List</h1>


<?php

$posts = forum_search(['category_name' => 'reminder', 'posts_per_page' => 20]);

foreach( $posts as $post ) {
    echo "<div>$post[post_title]</div>";
}

