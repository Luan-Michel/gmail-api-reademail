<?php
require __DIR__ . '/vendor/autoload.php';

// if (php_sapi_name() != 'cli') {
//     throw new Exception('This application must be run on the command line.');
// }

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Gmail API PHP Quickstart');
    $client->setScopes(Google_Service_Gmail::GMAIL_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            echo "Open the following link in your browser:\n".$authUrl."\n";
            echo 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Gmail($client);

// Print the labels in the user's account.
$user = 'me';
$results = $service->users_labels->listUsersLabels($user);

if (count($results->getLabels()) == 0) {
  echo "No labels found.\n";
} else {
  echo "Labels:<br>";
  foreach ($results->getLabels() as $label) {
    echo $label->getName()."<br>";
  }
}

$results = $service->users_messages->listUsersMessages($user);

echo "<pre>";
//var_dump($results);

if (count($results->getMessages()) == 0) {
  echo "No messages found.\n";
} else {
  echo "Messages:<br>";
  foreach ($results->getMessages() as $message) {
    echo "Email: "./*$service->users_messages->get($user, $message->id)->getSnippet().*/"<br><br>";
    $bodys = $service->users_messages->get($user, $message->id)->payload["parts"];
    foreach($bodys as $b){
      echo base64_decode($b->body->data)."<br>";
      //var_dump(base64_decode($service->users_messages->get($user, $message->id)->payload["body"]->data));
    }
  }
}

// $results = $service->users_threads->listUsersThreads($user);
//
// echo "<pre>";
// var_dump($results);
//
// if (count($results->getThreads()) == 0) {
//   echo "No messages found.\n";
// } else {
//   echo "Messages:<br>";
//   foreach ($results->getThreads() as $message) {
//     echo $service->users_threads->get($user, $message->id)->getMessages()."<br>";
//     //var_dump($service->users_messages->get($user, $message->id));
//   }
// }
