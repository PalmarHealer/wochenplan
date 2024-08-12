<?php
global $id, $pdo, $analyticsId;
if (GetUserSetting($id, "analytics", $pdo) == "true") {
    echo "
<!-- Google tag (gtag.js) -->
<script async src='https://www.googletagmanager.com/gtag/js?id=" . $analyticsId . "'></script>
<script>
    window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '" . $analyticsId . "');
</script>";

}
$pdo = null; ?>

