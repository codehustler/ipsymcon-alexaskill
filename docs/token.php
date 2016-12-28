<?
$token = $_POST['code'];

echo "{";
echo "\"access_token\": \"".$token."\",";
echo "\"token_type\": \"bearer\"";
echo "}";
?>