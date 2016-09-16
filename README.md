# Mixtape

Modify your Composer autoloader at runtime

## Installation

`composer require stechstudio/mixtape`

## Usage

First require your Composer autoloader like you normally do, make sure you assign a variable to reference the returned loader.

```php
$autoloader = require 'vendor/autoloader.php';
```

Now you can create a new Mixtape loader:

```php
$mixtape = new STS\Mixtape\Loader($autoloader);
```

And from there you can call `replacePsr4($prefix, paths)` or `removePsr4($prefix)` to alert the loader.

```php
$mixtape->replacePsr4('My\\Namespace', '/path/to/different/folder');
```
