# Private Packagist API Client

## Table of Contents
<!--ts-->
   * [Private Packagist API Client](#private-packagist-api-client)
      * [Table of Contents](#table-of-contents)
      * [Requirements](#requirements)
      * [Install](#install)
      * [Basic usage of private-packagist/api-client client](#basic-usage-of-private-packagistapi-client-client)
      * [Documentation](#documentation)
         * [Organization](#organization)
            * [Trigger a full synchronization](#trigger-a-full-synchronization)
         * [Team](#team)
            * [List an organization's teams](#list-an-organizations-teams)
            * [List all private packages a team has access to](#list-all-private-packages-a-team-has-access-to)
            * [Grant a team access to a list of private packages](#grant-a-team-access-to-a-list-of-private-packages)
            * [Remove access for a package from a team](#remove-access-for-a-package-from-a-team)
         * [Customer](#customer)
            * [List an organization's customers](#list-an-organizations-customers)
            * [Show a customer](#show-a-customer)
            * [Create a customer](#create-a-customer)
            * [Edit a customer](#edit-a-customer)
            * [Delete a customer](#delete-a-customer)
            * [Enable a customer](#enable-a-customer)
            * [Disable a customer](#disable-a-customer)
            * [List a customer's packages](#list-a-customers-packages)
            * [Grant a customer access to a package or edit the limitations](#grant-a-customer-access-to-a-package-or-edit-the-limitations)
            * [Revoke access to a package from a customer](#revoke-access-to-a-package-from-a-customer)
            * [Regenerate a customer's Composer repository token](#regenerate-a-customers-composer-repository-token)
         * [Subrepository](#subrepository)
            * [List an organization's subrepositories](#list-an-organizations-subrepositories)
            * [Show a subrepository](#show-a-subrepository)
            * [Create a subrepository](#create-a-subrepository)
            * [Delete a subrepository](#delete-a-subrepository)
            * [List a subrepositories's teams](#list-a-subrepositoriess-teams)
            * [Add a team to a subrepository or edit the permission](#add-a-team-to-a-subrepository-or-edit-the-permission)
            * [Remove a team from a subrepository](#remove-a-team-from-a-subrepository)
            * [List a subrepositories's packages](#list-a-subrepositoriess-packages)
            * [Show a subrepository package](#show-a-subrepository-package)
            * [Create a vcs package in a subrepository](#create-a-vcs-package-in-a-subrepository)
            * [Create a vcs package with credentials in a subrepository](#create-a-vcs-package-with-credentials-in-a-subrepository)
            * [Create a custom package in a subrepository](#create-a-custom-package-in-a-subrepository)
            * [Create a custom package with credentials in a subrepository](#create-a-custom-package-with-credentials-in-a-subrepository)
            * [Edit a vcs package in a subrepository in a subrepository](#edit-a-vcs-package-in-a-subrepository-in-a-subrepository)
            * [Edit a custom package in a subrepository](#edit-a-custom-package-in-a-subrepository)
            * [Delete a package from a subrepository](#delete-a-package-from-a-subrepository)
            * [List all dependents of a subrepository package](#list-all-dependents-of-a-subrepository-package)
            * [List a subrepositories's authentication tokens](#list-a-subrepositoriess-authentication-tokens)
            * [Create a subrepository authentication token](#create-a-subrepository-authentication-token)
            * [Delete a subrepository authentication token](#delete-a-subrepository-authentication-token)
            * [Regenerate a subrepository authentication token](#regenerate-a-subrepository-authentication-token)
            * [List a subrepositories's mirrored repositories](#list-a-subrepositoriess-mirrored-repositories)
            * [Show a mirrored repository](#show-a-mirrored-repository)
            * [Add mirrored repositories to a subrepository](#add-mirrored-repositories-to-a-subrepository)
            * [Edit the mirroring behaviour of mirrored repository in a subrepository](#edit-the-mirroring-behaviour-of-mirrored-repository-in-a-subrepository)
            * [Delete a mirrored repository from a subrepository](#delete-a-mirrored-repository-from-a-subrepository)
            * [List all mirrored packages from a mirrored repository in a subrepository](#list-all-mirrored-packages-from-a-mirrored-repository-in-a-subrepository)
            * [Add mirrored packages from one mirrored repository to a subrepository](#add-mirrored-packages-from-one-mirrored-repository-to-a-subrepository)
            * [Remove all mirrored packages from one mirrored repository in a subrepository](#remove-all-mirrored-packages-from-one-mirrored-repository-in-a-subrepository)
         * [Package](#package)
            * [List an organization's packages](#list-an-organizations-packages)
            * [Show a package](#show-a-package)
            * [Create a vcs package](#create-a-vcs-package)
            * [Create a vcs package with credentials](#create-a-vcs-package-with-credentials)
            * [Create a vcs package with a specific type](#create-a-vcs-package-with-a-specific-type)
            * [Create a custom package](#create-a-custom-package)
            * [Create a custom package with credentials](#create-a-custom-package-with-credentials)
            * [Edit a vcs package](#edit-a-vcs-package)
            * [Edit a custom package](#edit-a-custom-package)
            * [Delete a package](#delete-a-package)
            * [List all dependents of a package](#list-all-dependents-of-a-package)
            * [List all customers with access to a package](#list-all-customers-with-access-to-a-package)
            * [Create an artifact package file](#create-an-artifact-package-file)
            * [Create an artifact package](#create-an-artifact-package)
            * [Update artifact files of a package](#update-artifact-files-of-a-package)
            * [Add an artifact file to an existing package](#add-an-artifact-file-to-an-existing-package)
         * [Credential](#credential)
            * [List an organization's credentials](#list-an-organizations-credentials)
            * [Show a credential](#show-a-credential)
            * [Create a credential](#create-a-credential)
            * [Edit a credential](#edit-a-credential)
            * [Delete a credential](#delete-a-credential)
         * [Mirrored Repository](#mirrored-repository)
            * [List an organization's mirrored repositories](#list-an-organizations-mirrored-repositories)
            * [Show a mirrored repository](#show-a-mirrored-repository-1)
            * [Create a mirrored repository](#create-a-mirrored-repository)
            * [Edit a mirrored repository](#edit-a-mirrored-repository)
            * [Delete a mirrored repository](#delete-a-mirrored-repository)
            * [List all mirrored packages from one repository](#list-all-mirrored-packages-from-one-repository)
            * [Add mirrored packages from one repository](#add-mirrored-packages-from-one-repository)
            * [Remove all mirrored packages from one repository](#remove-all-mirrored-packages-from-one-repository)
         * [Job](#job)
            * [Show a job](#show-a-job)
            * [Wait for a job to finish](#wait-for-a-job-to-finish)
         * [Magento legacy keys](#magento-legacy-keys)
            * [List all legacy keys for a customer](#list-all-legacy-keys-for-a-customer)
            * [Create a new legacy keys for a customer](#create-a-new-legacy-keys-for-a-customer)
            * [Delete a legacy keys from a customer](#delete-a-legacy-keys-from-a-customer)
         * [Validate incoming webhook payloads](#validate-incoming-webhook-payloads)
      * [License](#license)

<!-- Added by: wissem, at: Thu Oct 15 10:37:57 CEST 2020 -->

<!--te-->

## Requirements

* PHP >= 5.6
* [Guzzle](https://github.com/guzzle/guzzle) library,

## Install

Via Composer:

```bash
$ composer require private-packagist/api-client php-http/guzzle6-adapter
```

Why do you need to require `php-http/guzzle6-adapter`? We are decoupled from any HTTP messaging client with help by [HTTPlug](http://httplug.io/), so you can pick an HTTP client of your choice, guzzle is merely a recommendation.

## Basic usage of `private-packagist/api-client` client

```php
<?php

// This file is generated by Composer
require_once __DIR__ . '/vendor/autoload.php';

$client = new \PrivatePackagist\ApiClient\Client();
$client->authenticate('api-token', 'api-secret');
$packages = $client->packages()->all();
```

From `$client` object, you can access the full Private Packagist API.

## Documentation

Full documentation can be found in the [Private Packagist documentation](https://packagist.com/docs/api).

### Organization

#### Trigger a full synchronization
```php
$jobs = $client->organization()->sync();
```
Returns an array of created jobs. One for every synchronization.

### Team

#### List an organization's teams
```php
$teams = $client->teams()->all();
```
Returns an array of teams.

#### List all private packages a team has access to
```php
$teamId = 1;
$packages = $client->teams()->packages($teamId);
```
Returns an array of packages.

#### Grant a team access to a list of private packages
```php
$teamId = 1;
$packages = $client->teams()->addPackages($teamId, ['acme-website/package']);
```
Returns an array of packages.

#### Remove access for a package from a team
```php
$teamId = 1;
$packages = $client->teams()->removePackage($teamId, 'acme-website/package');
```

### Customer

#### List an organization's customers
```php
$customers = $client->customers()->all();
```
Returns an array of customers.

#### Show a customer
```php
$customerId = 42;
$customer = $client->customers()->show($customerId);
// or
$customerUrlName = 'customer-url-name';
$customer = $client->customers()->show($customerUrlName);
```
Returns a single customer.

#### Create a customer
```php
$customer = $client->customers()->create('New customer name');
// or
$customer = $client->customers()->create('New customer name', false, 'customer-url-name', 'beta');
```
Returns the customer.

#### Edit a customer
```php
$customerId = 42;
$customerData = [
    'name' => $name,
    'urlName' => 'customer',
    'accessToVersionControlSource' => false,
    'minimumAccessibleStability' => 'beta',
];
$customer = $client->customers()->edit($customerId, $customerData);
```
Returns the customer.

#### Delete a customer
```php
$customerId = 42;
$client->customers()->remove($customerId);
```

#### Enable a customer
```php
$customerId = 42;
$customer = $client->customers()->enable($customerId);
```

#### Disable a customer
```php
$customerId = 42;
$customer = $client->customers()->disable($customerId);
```

#### List a customer's packages
```php
$customerId = 42;
$packages = $client->customers()->listPackages($customerId);
```
Returns an array of customer packages.

#### Grant a customer access to a package or edit the limitations
```php
$customerId = 42;
$packages = [
    [
        'name' => 'acme-website/package',
        'versionConstraint' => '^1.0 | ^2.0', // optional version constraint to limit updates the customer receives
        'expirationDate' => (new \DateTime())->add(new \DateInterval('P1Y'))->format('c'), // optional expiration date to limit updates the customer receives
        'minimumAccessibleStability' => 'beta', // optional stability to restrict customers to specific package version stabilities like alpha, beta, or RC
    ],
];
$packages = $client->customers()->addOrEditPackages($customerId, $packages);
```
Returns an array of all added or edited customer packages.

#### Revoke access to a package from a customer
```php
$customerId = 42;
$packageName = 'acme-website/package';
$client->customers()->removePackage($customerId, $packageName);
```

#### Regenerate a customer's Composer repository token
```php
$customerId = 42;
$confirmation = [
    'IConfirmOldTokenWillStopWorkingImmediately' => true,
];
$composerRepository = $client->customers()->regenerateToken($customerId, $confirmation);
```
Returns the edited Composer repository.

### Subrepository

#### List an organization's subrepositories
```php
$subrepositories = $client->subrepositories()->all();
```
Returns an array of subrepositories.

#### Show a subrepository
```php
$subrepositoryName = 'subrepository';
$subrepository = $client->subrepositories()->show($subrepositoryName);
```
Returns a single subrepository.

#### Create a subrepository
```php
$subrepository = $client->subrepositories()->create('New subrepository name');
```
Returns the subrepository.

#### Delete a subrepository
```php
$subrepositoryName = 'subrepository';
$client->subrepositories()->remove($subrepositoryName);
```

#### List a subrepositories's teams
```php
$subrepositoryName = 'subrepository';
$teams = $client->subrepositories()->listTeams($subrepositoryName);
```
Returns an array of subrepositories teams.

#### Add a team to a subrepository or edit the permission
```php
$subrepositoryName = 'subrepository';
$teams = [
    [
        'id' => 12,
        'permission' => 'owner',
    ],
];
$teams = $client->subrepositories()->addOrEditTeams($subrepositoryName, $teams);
```
Returns an array of added subrepository teams.


#### Remove a team from a subrepository
```php
$subrepositoryName = 'subrepository';
$teamId = 12;
$client->subrepositories()->removeTeam($subrepositoryName, $teamId);
```

#### List a subrepositories's packages
```php
$subrepositoryName = 'subrepository';
$packages = $client->subrepositories()->packages()->all($subrepositoryName);
```
Returns an array of subrepositories packages.

#### Show a subrepository package
```php
$subrepositoryName = 'subrepository';
$package = $client->subrepositories()->packages()->show($subrepositoryName, 'acme-website/package');
```
Returns the package.

#### Create a vcs package in a subrepository
```php
$subrepositoryName = 'subrepository';
$job = $client->subrepositories()->packages()->createVcsPackage($subrepositoryName, 'https://github.com/acme-website/package');
```
Returns a new job.

#### Create a vcs package with credentials in a subrepository
```php
$subrepositoryName = 'subrepository';
$credentialId = 42;
$job = $client->subrepositories()->packages()->createVcsPackage($subrepositoryName,'https://github.com/acme-website/package', $credentialId);
```
Returns a new job.

#### Create a custom package in a subrepository
```php
$subrepositoryName = 'subrepository';
$packageDefinition = '{...}';
$job = $client->subrepositories()->packages()->createCustomPackage($subrepositoryName, $packageDefinition);
```
Returns a new job.

#### Create a custom package with credentials in a subrepository
```php
$subrepositoryName = 'subrepository';
$packageDefinition = '{...}';
$credentialId = 42;
$job = $client->subrepositories()->packages()->createCustomPackage($subrepositoryName, $packageDefinition, $credentialId);
```
Returns a new job.

#### Edit a vcs package in a subrepository in a subrepository
```php
$subrepositoryName = 'subrepository';
$job = $client->subrepositories()->packages()->editVcsPackage($subrepositoryName, 'acme-website/package', 'https://github.com/acme-website/package');
```
Returns a new job.

#### Edit a custom package in a subrepository
```php
$subrepositoryName = 'subrepository';
$packageDefinition = '{...}';
$job = $client->subrepositories()->packages()->editCustomPackage($subrepositoryName, 'acme-website/package', $packageDefinition);
```
Returns a new job.

#### Delete a package from a subrepository
```php
$subrepositoryName = 'subrepository';
$client->subrepositories()->packages()->remove($subrepositoryName, 'acme-website/package');
```

#### List all dependents of a subrepository package
```php
$subrepositoryName = 'subrepository';
$client->subrepositories()->packages()->listDependents($subrepositoryName, 'acme-website/package');
```
Returns a list of packages.

#### List a subrepositories's authentication tokens
```php
$subrepositoryName = 'subrepository';
$tokens = $client->subrepositories()->listTokens($subrepositoryName);
```
Returns an array of authentication tokens.

#### Create a subrepository authentication token
```php
$subrepositoryName = 'subrepository';
$data = [
  'description' => 'Subrepository Token',
  'access' => 'read',
];
$token = $client->subrepositories()->createToken($subrepositoryName, $data);
```
Returns the authentication token.

#### Delete a subrepository authentication token
```php
$subrepositoryName = 'subrepository';
$tokenId = 33;
$client->subrepositories()->removeToken($subrepositoryName, $tokenId);
```

#### Regenerate a subrepository authentication token
```php
$subrepositoryName = 'subrepository';
$tokenId = 33;
$confirmation = [
    'IConfirmOldTokenWillStopWorkingImmediately' => true,
];
$token = $client->subrepositories()->regenerateToken($subrepositoryName, $confirmation);
```
Returns the authentication token.

#### List a subrepositories's mirrored repositories
```php
$subrepositoryName = 'subrepository';
$mirroredRepositories = $client->subrepositories()->mirroredRepositories()->all($subrepositoryName);
```
Returns an array of mirrored repositories.

#### Show a mirrored repository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$mirroredRepository = $client->subrepositories()->mirroredRepositories()->show($subrepositoryName, $mirroredRepositoryId);
```
Returns the mirrored repository.

#### Add mirrored repositories to a subrepository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoriesToAdd = [
    ['id' => 12, 'mirroringBehavior' => 'add_on_use'],
];
$mirroredRepository = $client->subrepositories()->mirroredRepositories()->add($subrepositoryName, $mirroredRepositoriesToAdd);
```
Returns a list of added mirrored repositories.

#### Edit the mirroring behaviour of mirrored repository in a subrepository 
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$mirroredRepository = $client->subrepositories()->mirroredRepositories()->create($subrepositoryName, $mirroredRepositoryId, 'add_on_use');
```
Returns the edited mirrored repository.

#### Delete a mirrored repository from a subrepository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$client->subrepositories()->mirroredRepositories()->remove($subrepositoryName, $mirroredRepositoryId);
```

#### List all mirrored packages from a mirrored repository in a subrepository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$packages = $client->subrepositories()->mirroredRepositories()->listPackages($subrepositoryName, $mirroredRepositoryId);
```
Returns an array of packages.

#### Add mirrored packages from one mirrored repository to a subrepository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$packages = [
    'acme/cool-lib
];
$jobs = $client->subrepositories()->mirroredRepositories()->addPackages($subrepositoryName, $mirroredRepositoryId, $packages);
```
Returns an array of jobs.

#### Remove all mirrored packages from one mirrored repository in a subrepository
```php
$subrepositoryName = 'subrepository';
$mirroredRepositoryId = 42;
$client->subrepositories()->mirroredRepositories()->removePackages($subrepositoryName, $mirroredRepositoryId);
```

### Package

#### List an organization's packages
```php
$filters = [
    'origin' => \PrivatePackagist\ApiClient\Api\Packages::ORIGIN_PRIVATE, // optional filter to only receive packages that can be added to customers 
];
$packages = $client->packages()->all($filters);
```
Returns an array of packages.

#### Show a package
```php
$package = $client->packages()->show('acme-website/package');
```
Returns the package.

#### Create a vcs package
```php
$job = $client->packages()->createVcsPackage('https://github.com/acme-website/package');
```
Returns a new job.

#### Create a vcs package with credentials
```php
$credentialId = 42;
$job = $client->packages()->createVcsPackage('https://github.com/acme-website/package', $credentialId);
```
Returns a new job.

#### Create a vcs package with a specific type
```php
$job = $client->packages()->createVcsPackage('https://github.com/acme-website/package', null, 'git');
```
Returns a new job.

#### Create a custom package
```php
$packageDefinition = '{...}';
$job = $client->packages()->createCustomPackage($packageDefinition);
```
Returns a new job.

#### Create a custom package with credentials
```php
$packageDefinition = '{...}';
$credentialId = 42;
$job = $client->packages()->createCustomPackage($packageDefinition, $credentialId);
```
Returns a new job.

#### Edit a vcs package
```php
$job = $client->packages()->editVcsPackage('acme-website/package', 'https://github.com/acme-website/package');
```
Returns a new job.

#### Edit a custom package
```php
$packageDefinition = '{...}';
$job = $client->packages()->editCustomPackage('acme-website/package', $packageDefinition);
```
Returns a new job.

#### Delete a package
```php
$client->packages()->remove('acme-website/package');
```

#### List all dependents of a package
```php
$client->packages()->listDependents('acme-website/package');
```
Returns a list of packages.

#### List all customers with access to a package
```php
$client->packages()->listCustomers('acme-website/package');
```
Returns a list of customers with access to the package.

#### Create an artifact package file

```php
$fileName = 'package1.zip'; // your package archive artifact containing a valid composer.json in root directory
$file = file_get_contents($fileName);
$client->packages()->artifacts()->create($file, 'application/zip', $fileName);
```

#### Create an artifact package

```php
$fileName = 'package1.zip';
$file = file_get_contents($fileName);
$response = $client->packages()->artifacts()->create($file, 'application/zip', $fileName);
$artifactId = $response['id'];
$client->packages()->createArtifactPackage([$artifactId]);
```
#### Update artifact files of a package

```php
$result = $client->packages()->artifacts()->showPackageArtifacts('acme-website/package'); // get artifact files details for a package
$artifactFileIds = [42, 43];
$client->packages()->editArtifactPackage('acme-website/package', $artifactFileIds);
```

#### Add an artifact file to an existing package

```php
$packageName = 'acme/artifact';
$fileName = 'package1.zip';
$file = file_get_contents($fileName);
$client->packages()->artifacts()->add($packageName, $file, 'application/zip', $fileName);
```
### Credential

#### List an organization's credentials
```php
$credentials = $client->credentials()->all();
```
Returns an array of credentials.

#### Show a credential
```php
$credentialId = 42;
$credential = $client->credentials()->show($credentialId);
```
Returns the credential.

#### Create a credential
```php
$type = \PrivatePackagist\ApiClient\Api\Credentials::TYPE_HTTP_BASIC;
$credential = $client->credentials()->create('ACME http auth', $type, 'acme-website.com', 'username', 'password');
```
Returns the new credential.

#### Edit a credential
```php
$credentialId = 42;
$type = \PrivatePackagist\ApiClient\Api\Credentials::TYPE_HTTP_BASIC;
$credential = $client->credentials()->edit($credentialId, $type, 'username', 'password');
```
Returns the edited credential.

#### Delete a credential
```php
$credentialId = 42;
$client->credentials()->remove($credentialId);
```

### Mirrored Repository

#### List an organization's mirrored repositories
```php
$mirroredRepositories = $client->mirroredRepositories()->all();
```
Returns an array of mirrored repositories.

#### Show a mirrored repository
```php
$mirroredRepositoryId = 42;
$mirroredRepository = $client->mirroredRepositories()->show($mirroredRepositoryId);
```
Returns the mirrored repository.

#### Create a mirrored repository
```php
$mirroredRepository = $client->mirroredRepositories()->create('Mirrored Repository', 'https://composer.example.com', 'add_on_use', 543);
```
Returns the new mirrored repository.

#### Edit a mirrored repository
```php
$mirroredRepositoryId = 42;
$mirroredRepository = $client->mirroredRepositories()->create($mirroredRepositoryId, 'Mirrored Repository', 'https://composer.example.com', 'add_on_use', 543);
```
Returns the edited mirrored repository.

#### Delete a mirrored repository
```php
$mirroredRepositoryId = 42;
$client->mirroredRepositories()->remove($mirroredRepositoryId);
```

#### List all mirrored packages from one repository
```php
$mirroredRepositoryId = 42;
$packages = $client->mirroredRepositories()->listPackages($mirroredRepositoryId);
```
Returns an array of packages.

#### Add mirrored packages from one repository
```php
$mirroredRepositoryId = 42;
$packages = [
    'acme/cool-lib
];
$jobs = $client->mirroredRepositories()->addPackages($mirroredRepositoryId, $packages);
```
Returns an array of jobs.

#### Remove all mirrored packages from one repository
```php
$mirroredRepositoryId = 42;
$client->mirroredRepositories()->removePackages($mirroredRepositoryId);
```

### Job

#### Show a job
```php
$job = $client->jobs()->show($jobId);
```
Returns the job.

#### Wait for a job to finish
This will periodically poll the job status until the job either finished or the maximum wait time was reached
```php
$numberOfSecondsToWait = 180;
$jobHelper = new \PrivatePackagist\ApiClient\JobHelper($client);
try {
    $job = $jobHelper->waitForJob($jobId, $numberOfSecondsToWait);
} catch (\PrivatePackagist\ApiClient\Exception\JobTimeoutException $e) {
    // Job didn't finish within the specified time
} catch (\PrivatePackagist\ApiClient\Exception\JobErrorException $e) {
    // Job finished with an error. See the message for more information
    echo $e->getMessage();
}



```
Returns the job.

### Magento legacy keys

#### List all legacy keys for a customer
```php
$customerId = 42;
$legacyKeys = $client->customers()->magentoLegacyKeys()->all($customerId);
```
Returns a list of Magento legacy keys.

#### Create a new legacy keys for a customer
```php
$legacyKey = $client->customers()->magentoLegacyKeys()->create($customerId, $publicKey, $privateKey);
```
Returns the new Magento legacy key.

#### Delete a legacy keys from a customer
```php
$client->customers()->magentoLegacyKeys()->remove($customerId, $publicKey);
```

### Validate incoming webhook payloads

When you create or update a webhook in Private Packagist an optional secret can be set. This secret gets used to create a signature which is sent with each request in the headers as `Packagist-Signature`. The secret and signature can then be used on your server to validate that the request was made by Private Packagist. If no secret is set then no signature is sent.

```php
$request = /** any Psr7 request */;
$secret = 'webhook-secret';
$webhookSignature = new \PrivatePackagist\ApiClient\WebhookSignature($secret);
$requestSignature = $request->hasHeader('Packagist-Signature') ? $request->getHeader('Packagist-Signature')[0] : null; 
$webhookSignature->validate($requestSignature, (string) $request->getBody());
```

## License

`private-packagist/api-client` is licensed under the MIT License
