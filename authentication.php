<?php
$params = array("response_type" => "code",
                "client_id" => "{8a52d96e-d719-4129-8b40-c0751569ceac}",
                "redirect_uri" => "http://localhost/eol/dorivit_retouren_index.php",
               );
$url = "https://start.exactonline.nl/api/oauth2/auth" . '?' . http_build_query($params);
header("Location: " . $url);
?>
