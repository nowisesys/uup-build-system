TARGETS
============================================

Targets should extend the abstract base class TargetBase. The Target class used in examples could be
used as guidance. 

### IMPLEMENTATION:

A class that represent some job that processes a file to produce data used by other jobs might be 
implemented something like this:

```php
class FileProcessorTarget extends TargetBase
{
    private string $filename;
    private string $lockfile;
    private string $lasttime;
    
    public function __construct(string $filename) 
    {
        $this->filename = $filename;
        $this->lockfile = sprintf("%s.lock", $filename);
        $this->lasttime = sprintf("%s.last", $filename);
    }
    
    public function isUpdated(): bool
    {
        return file_exists($this->lasttime) && filemtime($this->filename) < filemtime($this->lasttime);
    }
    
    public function rebuild(): void 
    {
        if (file_exists($this->lockfile)) {
            return;
        }
        
        try {
            touch($this->lockfile);        
            $this->process();     
            touch($this->lastfile);
        } finally {
            unlink($this->lockfile);
        }
    }
    
    public function getName(): string
    {
        return $this->filename;
    }
    
    private function process(): void 
    {        
        // ...
    }
}
```

Now we should put this class to work. Let's say that we got three files where two of them
needs another file to be processed first, then we could programmatically define this:

```php
$tree = new DependencyTree();
$tree->addDefinition(new GoalDefinition(new FileProcessorTarget("file1"));
$tree->addDefinition(new GoalDefinition(new FileProcessorTarget("file2"), ["file1"]);
$tree->addDefinition(new GoalDefinition(new FileProcessorTarget("file3"), ["file1"]);
```

To rebuild the complete tree, get the evaluator for root node:

```php
$tree->getEvaluator()->rebuild();
```

Because target file1 is a dependency of both file2 and file3, it will be built before the other
two targets.

### REAL WORLD:

The code above is purposely simple, in real world application we have more complex setups with
nodes depending on each other, even circular dependencies. In this case it's recommended to declare
all rules in a makefile.

### SIMULATION:

While running the examples are great for testing, the effect is best demonstrated by inserting a
small timeout in the example target class:

```php
class Target implements TargetInterface
{
    /**
     * @inheritdoc
     */
    public function rebuild(): void
    {
        printf("Called rebuild() on %s (updated=%b)\n", $this->name, $this->updated);
        sleep(3);
        $this->updated = true;
    }
    
    // ...
}
```

Now execute an example and see how target gets built in order with some delay that simulates
some work.

```shell
php example/definition-tree.php
```

### EXCEPTIONS:

It's recommended to throw exceptions from targets. It will immediately terminate the target
and stop child targets from being processed.

### LOCK FILE CONTROLLED:

The classes under the `UUP\BuildSystem\Target\Check` namespace could be used to for defining
lockfile controlled build targets.

A real-world example could be to define a target for migrating the database to latest.

```php
class Migrations extends AlwaysBuildTarget
{
    public function __construct()
    {
        parent::__construct("migrations");
    }

    public function perform(): void
    {
        $migrator = new DatabaseMigrator();
        $migrator->migrate();
    }

    public function getName(): string
    {
        return "Migrations";
    }

    public function getDescription(): string
    {
        return "Migrate the database to latest";
    }
}
```

The method `perform()` contains action to perform. This example assumes that there exists some hypothetical
class that performs the database migration. 

If we have some bootstrap of database to perform (like setup schema), then it would make sense to define a 
separate bootstrap run-once target. In the makefile we would then declare these targets to have migrations depend 
on bootstrap being run first:

```makefile
Bootstrap:
Migrations: Bootstrap
```

#### PERMISSION

By default, the build directory inside this package is used for lockfile storage. It can be overrun by passing
the location argument to constructor. Make sure the lockfile directory is writable by the user that runs the 
build steps.
