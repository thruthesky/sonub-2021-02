<?php

?>





<h1>DEFAULT THEME</h1>

<?php

d(profile());
d($_COOKIE);

?>

<h4>User: {{ sessionId() }}</h4>

{{ user }}

<hr>
You are using default theme. The theme you are access is [ <?php echo get_domain_theme()?> ].