<?php
require_once('vendor/autoload.php');
session_start();





$provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '1dec8b1b5aca4667924f373756d34b75',    // The client ID assigned to you by the provider
    'clientSecret'            => 'ahTmVKy0Zp93j3WDfrhrFVqcy1wChPiITwaUaDwr',   // The client password assigned to you by the provider
    'redirectUri'             => 'http://ec2-54-224-182-102.compute-1.amazonaws.com/eve/oauth.php',
    'urlAuthorize'            => 'https://login.eveonline.com/oauth/authorize',
    'urlAccessToken'          => 'https://login.eveonline.com/oauth/token',
    'urlResourceOwnerDetails' => 'https://login.eveonline.com/oauth/verify'
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    // Fetch the authorization URL from the provider; this returns the
    // urlAuthorize option and generates and applies any necessary parameters
    // (e.g. state).
    $authorizationUrl = $provider->getAuthorizationUrl();

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo "accesstoken: ".$accessToken->getToken() . "<br>\n";
        echo "refreshtoken: ".$accessToken->getRefreshToken() . "<br>\n";
        echo "expirey: ".$accessToken->getExpires() . "<br>\n";
        echo "accesstoken: ".($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>\n";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

        // The provider provides a way to get an authenticated API request for
        // the service, using the access token; it returns an object conforming
        // to Psr\Http\Message\RequestInterface.
        $request = $provider->getAuthenticatedRequest(
            'GET',
            'http://brentertainment.com/oauth2/lockdin/resource',
            $accessToken
        );

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}







?>