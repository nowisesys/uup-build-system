BUILD LOCATION
============================================

Information in this document is relevant when using lock file controlled targets as a
base for your target classes. See namespace `UUP\BuildSystem\Target\Check`.

Default behavior is to keep build files (last execution time and lock files) inside the 
vendor directory. This is probably not what you want, here's how to relocate this default
location.

### TRAIT:

Create a location trait that overrides the method `getLocation()` defining the location
for the build directory. A trait for this is bundled with this package that relocated the 
build directory to your project root.

```php
trait LocationTrait
{
    protected function getLocation(): string
    {
        return sprintf("%s/%s", __DIR__, '../../../../../../../build');
    }
}
```

### ARGUMENT:

Another option is to create a base class for your target classes that passes the location as 
second argument for derived class constructor:

```php
use UUP\BuildSystem\Target\Check\DependencyCheckingTarget as DependencyCheckingBase;

abstract class DependencyCheckingTarget extends DependencyCheckingBase
{
    public function __construct(string $filename)
    {
        parent::__construct($filename, "/tmp/build");
    }
}
```

### OVERRIDE:

Last option is to simply override the `getLocation()` to return your preferred location.

```php

abstract class DependencyCheckingTarget extends DependencyCheckingBase
{
    protected function getLocation(): string
    {
        return "/tmp/build";
    }
}
```
