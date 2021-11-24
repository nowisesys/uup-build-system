## UUP-BUILD-SYSTEM - 

A build system similar to make with complex dependency tree. Declare goals (target and dependencies) 
and evaluate the dependency tree to rebuild targets in correct order.

### TARGETS:

A target has a name and description as properties. The class provides the isUpdated() for checking 
if a target is up-to-date and rebuild() to build it. 

### GOALS:

Goals are defined by its target and a list of zero or more dependencies. Dependencies are strings
matching other targets (goals).

### NODES & TREES:

The dependency tree can be constructed manually by adding child nodes (dependencies) and then
using the node evaluator on the root node for rebuilding the manual crafted tree. More convenient
is to use the dependency tree, adding nodes to it either as dependency nodes or using goal 
definitions.

### EXAMPLES:

The example directory contains some script for constructing an example build tree. Evaluating
the T5 node should rebuild T1, T2, T3, T5, T7 and T8 in that order (unless dependency node are 
already up-to-date).

![](docs/dependency-tree.png)

Run them from command line:

```shell
php example/definition-tree.php
```
