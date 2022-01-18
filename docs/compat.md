COMPATIBILITY MODE
============================================

When using compat-mode, then pbsmake work almost like standard make. The child targets that depends 
on current target is not executed.

```shell
./bin/pbsmake example/file/input.make compat target=T1 
Called isUpdated() on T1 (updated=0) ([])
Called rebuild() on T1 (updated=0)
```

The default behavior is otherwise to rebuild all tree paths that depends on current target. That includes
any parent nodes, the target node itself and any child nodes depending on current target.

### FILE TYPES:

Whether to use files with Makefile or JSON declaration is a matter of taste. Using Makefile is usually 
better for development while being more compact and simply to overview, while JSON lends itself better for 
automation.

### DECLARATION:

The default is to declare phony targets (i.e. dist-clean) on right-hand side.

```makefile
clean: dist-clean
	Shell("rm -f *.bak")
all-clean: dist-clean
	Shell("rm -f *~")
```

It's also possible to use GNU makefile rule-declarations. In this case (due to a current limitation in 
parsing rules) the target identifiers must be declared before the phony target.

```makefile
clean:
	Shell("rm -f *.bak")
all-clean:
	Shell("rm -f *~")

dist-clean: clean all-clean
```

### RUNNING:

When using the latter form of rule declarations, append `compat` to command line options to get correct
behavior:

```shell
./bin/pbsmake example/file/input.make compat target=clean
rm -f *.bak
./bin/pbsmake example/file/input.make compat target=all-clean
rm -f *~
./bin/pbsmake example/file/input.make compat target=dist-clean
rm -f *.bak
rm -f *~
```

Failures to use compat mode with GNU makefile style of rule declarations will lead to unexpected behavior
as seen:

```shell
./bin/pbsmake example/file/input.make target=clean
rm -f *.bak
rm -f *.bak
rm -f *~
```

### JSON FILES:

This is how previous GNU style rules would be declared in JSON files.

```json lines
        "clean": {
            "class": "Shell",
            "arguments": [
                "rm -f *.bak"
            ],
            "dependencies": [
            ]
        },
        "all-clean": {
            "class": "Shell",
            "arguments": [
                "rm -f *~"
            ],
            "dependencies": [
            ]
        },
        "dist-clean": {
            "dependencies": [
                "clean",
                "all-clean"
            ]
        }
```

The dist-clean is as before a phony target. Executing the clean-rules yields the same behavior.

```shell
./bin/pbsmake example/file/input.json compat target=dist-clean
rm -f *.bak
rm -f *~
```

### LIMITATIONS:

1. Target rules must be declared before used in phony targets.
2. Only one target class per rule.
3. Phony targets can't be redefined as real targets.

Note (1) is a consequence of (3) that means if `clean` was defined in the phony target list, then 
redeclare it later on would not replace the dependency node.

Note (2) is in practice not that limiting. We can execute multiple commands in same shell target and
create phony targets to have multiple target dependencies (as seen above in dist-clean).
