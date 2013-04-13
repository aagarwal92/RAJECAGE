<?php
/* A very simple canvas app */

// get facebook-php-sdk from github: https://github.com/facebook/facebook-php-sdk
require 'facebook-php-sdk/src/facebook.php';

// app id and app secret from here: https://developers.facebook.com/apps
$fb = new Facebook(array(
  'appId' => '<YOUR APP ID>',
  'secret' => '<YOUR APP SECRET>',
));

$user = $fb->getUser();
if (!$user) { // if user has not authenticated your app
  $params = array(
    // the permissions you're requesting - click Extended Profile Properties or one of the other bullets here:
    // https://developers.facebook.com/docs/concepts/login/permissions-login-dialog/
    // no additional permissions are needed for our cover photo app, but I'll leave these in here as an example
    'scope' => 'user_relationships,friends_relationships',
    // this is where the user will be sent after they click Allow or Go To App on the permissions dialog
    'redirect_uri' => 'https://apps.facebook.com/<YOUR APP NAMESPACE>',
  );
  $login_url = $fb->getLoginUrl($params);
  print '<script>top.location.href = "' . $login_url . '"</script>'; //redirect the user to the permissions dialog
  exit();
}

// fetch the user's friends' cover photos
// play with the Graph API Explorer to test out queries: https://developers.facebook.com/tools/explorer
// click the Get Access Token button on that page to get more permissions for your test queries, but make
// sure you add them to the 'scope' above if you plan on actually using them in your app
$data = $fb->api('/me?fields=friends.fields(cover,name)');
$friends = $data['friends']['data']; // a proper app would also check for errors, this assumes it worked
shuffle($friends); // randomize the array
foreach ($friends as $friend) {
  // print the user's name and cover photo
  print $friend['name'] . '<br />';
  print '<img src="' . $friend['cover']['source'] . '" /><br />';
}
?>
