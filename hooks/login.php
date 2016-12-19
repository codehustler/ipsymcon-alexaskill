Login to IP Symcon
<?
IPS_LogMessage($_IPS['SELF'], "[login] query parameters: " . json_encode($_GET));

echo "<br />";
echo "<a href=\"" . $_GET['redirect_uri'] . "?code=123456789" . "&state=" . $_GET['state'] . "\">Connect my IPS with Alexa</a>";
echo "<br />";
?>