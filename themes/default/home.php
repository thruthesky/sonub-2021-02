<?php

?>





<h1>HOME</h1>

<?php

d(profile());
d($_COOKIE);

?>

<h4>User: {{ sessionId() }}</h4>

{{ user }}

