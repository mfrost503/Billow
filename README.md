# Billow

[![Build Status](https://travis-ci.org/mfrost503/Billow.svg?branch=master)](https://travis-ci.org/mfrost503/Billow)

Billow is a Digital Ocean API wrapper that allows you to retrieve information, provision
droplets (Virtual Machines) and perform actions on existing virtual machines. There are a 
couple different ways to interact with this library; convenience classes and direct client usage.

This README will serve as documentation for the library and provide examples of how to use
the library with the convenience classes and directly with the client.

### Client

`Billow\Client` acts as an intermediary between Billow and an HTTP Client. By default,
`Billow\Client` uses Guzzle 5.3 but it does make use of `Billow\ClientInterface` which allows
you to create your own Client implementations. If you wanted to use Guzzle 6, it would be very
easy to write a client that implements `Billow\ClientInterface` and write the implementations for the
`get` and `post` methods to suit your needs.

### DropletService

The `DropletService` provides an easy way to retrieve, create, and perform actions on Droplets. 
If there are specific configurations for Guzzle that you normally use, you can pass them to the
`Billow\Client` constructor, `Billow\Client` does not override the constructor; so the configurations
can be passed the same way you'd pass them to `GuzzleHttp\Client`. If a client is not set, the DropletService will instantiate an instance of the default client. The only time you'll need to inject an instance of `Billow\Client` is if you have a default configuration.

#### Create

The create method requires that you provide a request body, along with your access token as an 
Authorization header. Digital Ocean provides some documentation for what the request body should 
look like: [Digital Ocean API v2 Create Droplet](https://developers.digitalocean.com/documentation/v2/#create-a-new-droplet).
The body must be sent as an array, below is an example of the array that would be passed to the create method.

```php
$dropletBody = [
    'name' => 'My New Droplet',
    'region' => 'nyc2',
    'size' => '40gb',
    'image' => 'ubuntu-14-04-x64',
    'ssh_keys' => ['public key content'],
    'backups' => true,
    'ipv6' => true,
    'private_networking' => true,
    'user_data' => 'meta data to be associated with the droplet'
];
```

So if we use the example from above, the call to create a new box would look like the following:

```php
<?php
use Billow\DropletService;

$headers = ['Authorization' => 'Bearer xxxxxxxxx'];

// assuming the body of the request is the same as above
$dropletService = new DropletService();
$response = $dropletService->create($dropletBody, $headers);
```

The response is a `GuzzleHttp\Message\Response`, so if a response doesn't complete successfully;
you can still use the `getBody()` `getHeaders()` `getStatusCode()` methods for more specific information. In the event Guzzle throws an exception, Billow will throw a `Billow\Exceptions\ProvisionException` that will display the message from the response along with the status code and the previous exception.

#### Retrieve

The retrieve method requires that you provide a Droplet ID and the header information. If you do not provide a `Content-type` header, it will be created and default to `application\json`. Included in these headers should be your access token in an Authorization header.

Digital Ocean offers a couple different distributions, when you use the `DropletService` to retrieve a droplet it will return an instance of `Billow\Droplets\Droplet`. By making use of the `Billow\Droplets\DropletFactory`, retrieve will attempt to create a value object of the droplet you are trying to retrieve. For instance, if the droplet you retrieve is a Ubuntu droplet, the return type will be `Billow\Droplets\Ubuntu`.

Here's an example of the Droplet retrieval via the `DropletService`:

```php
<?php
use Billow\DropletService;

$headers = [
    'Authorization' => 'Bearer xxxxxxx'
];

$dropletId = 123456;

$dropletService = new DropletService();
$droplet = $dropletService->retrieve($dropletId, $headers);

echo $droplet->toJSON();
```

If Guzzle throws an exception, it will be caught and rethrown as a `Billow\Exceptions\DropletException`

#### Performing Actions

Digital Ocean provides the ability to perform actions such as: rebooting, powering off, powering on, and many more from their API. `DropletService` makes this process relatively painless with the `performAction()` method. All the available actions can be instantiated and passed to the `performAction(DropletInterface $droplet, ActionInterface $action, $headers)` method. In some cases, rename for instance, a constructor parameter will be required. Here are 2 examples, one using a constructor parameter and one without.

```php
<?php
use Billow\DropletService;
use Billow\Actions\EnableBackups;

$headers = [
    'Authorization' => 'Bearer xxxxxxxx'
];
$enableBackups = new EnableBackups();
$dropletId = 123456;

$dropletService = new DropletService();
$droplet = $dropletService->retrieve($dropletId);

$response = $dropletService->performAction($droplet, $enableBackups, $headers);
```

```php
<?php
use Billow\DropletService;
use Billow\Actions\Rename;

$headers = [
    'Authorization' => 'Bearer xxxxxxxx'
];
$rename = new Rename('my-renamed-droplet');
$dropletId = 123456;

$dropletService = new DropletService();
$droplet = $dropletService->retrieve($dropletId, $headers);

$response = $dropletService->performAction($droplet, $rename, $headers);
```
    
