## UUP-BUILD-SYSTEM

A build system similar to make with complex dependency tree. Declare goals (target and dependencies) 
and evaluate the dependency tree to rebuild targets in correct order.

Goals are either defined programmatically in code or declared in one or more files. The remaining job for
users are to implement the target interface with some concrete actions.

### GETTING STARTED:

* Begin by creating a dependency tree.
* Add one or more goal definitions. A goal definition consists of the target (code to run) and a list of dependencies.
* Get the node evaluator for complete tree or a child node.
* Call rebuild() to build that node, its dependencies and child nodes.

***Hint:*** 
The dependency tree can be obtained from a (make/json) file reader.

### TARGETS:

A [target](docs/targets.md) has a unique name and description as properties. The class provides the `isUpdated()` for 
checking if a target is up-to-date and `rebuild()` to build it. The name is used for other targets to express their 
dependency on this target.

### GOALS:

Goals are defined by its target and a list of zero or more dependencies. Dependencies are strings matching 
other targets (goals) by their name. A goal is what's used for constructing the dependency tree.

### DECLARATIONS:

What, when & how everything should be build can either be declared programmatically in code, static declared with 
files or a mixture of them.

### NODES & TREES:

The dependency tree can be constructed manually by adding child nodes (dependencies) and then
using the node evaluator on the root node for rebuilding the manual crafted tree. More convenient
is to use the dependency tree, adding nodes to it either as dependency nodes or using goal 
definitions.

### FILES:

Dependencies can be declared in text files which are consumed by a file reader. The same reader
can be used for reading rules from multiple input files:

```php
$reader = new MakeFileReader();

$reader->addDependencies("makefile1.txt");
$reader->addDependencies("makefile2.txt");

$reader->getDependencyTree()
       ->getEvaluator()
       ->rebuild();
```

Currently, [GNU makefile](example/file/input.make) or [JSON](example/file/input.json) is the
supported file formats.

#### MAKEFILE:

An example of makefile declaration is this:

```makefile
VERBOSE	:= true
DEBUG 	:= true

NAMESPACE := UUP\BuildSystem\Tests

T1 :
	Target("T1")
T2 : T1
	Target("T2")
T3 : T1
	Target("T3")
T4 : T2
	Target("T4")
T5 : T2 T3
	Target("T5")
T6 : T4
	Target("T6")
T7 : T5
	Target("T7")
T8 : T5
	Target("T8")
```

As usual, the left-hand side is the rule target and right-hand side list dependencies. The Target class implements 
the PHP code to execute for that rule target. The T5 target depends on T2 and T3, while T6 and T7 both depends on T5.

In reality, the Target class will be replaced by different classes. This is just an example makefile purely for 
testing.

#### NAMESPACES:

The default namespace is declared in the makefile. If classes is placed in multiple namespaces, either 
declare them fully qualified or split declarations in multiple file, each with their own default namespace.

### EVALUATION:

The tree is usually completely rebuilt by evaluating its root node:

```php 
$tree->getEvaluator()->rebuild();
```

It's also possible to obtain one of the tree nodes from the registry and evaluate it:

```php
$tree->getRegistry()->getNode("T5")
    ->getEvaluator()
    ->rebuild();
```

### EXAMPLES:

The example directory contains some script for constructing an example build tree. Evaluating
the T5 node should rebuild T1, T2, T3, T5, T7 and T8 in that order (unless dependency node are 
already up-to-date).

![](docs/dependency-tree.png)

Run them from command line:

```shell
php example/definition-tree.php
```

### EXCLUDE CHILD TARGETS:

The default is to build a target node with all its dependency and child nodes. For a more [standard
make mode](example/make-compat.php), building child nodes can be disabled:

```php
$tree->getEvaluator()->setRebuildChildren(false)->rebuild();
```

Then the output will be:

```text
++ Rebuild node T5:
Called isUpdated() on T1 (updated=0)
Called rebuild() on T1 (updated=0)
Called isUpdated() on T2 (updated=0)
Called rebuild() on T2 (updated=0)
Called isUpdated() on T3 (updated=0)
Called rebuild() on T3 (updated=0)
Called isUpdated() on T5 (updated=0)
Called rebuild() on T5 (updated=0)
++ Rebuild complete tree:
```
