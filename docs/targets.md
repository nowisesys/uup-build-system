TARGETS
============================================

Targets should implement the interface TargetInterface. The Target class used in examples could be
used as guidance. 

### IMPLEMENTATION:

A class that represent some job that processes a file to produce data used by other jobs might be 
implemented something like this:

```php
class FileProcessorTarget implements TargetInterface 
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