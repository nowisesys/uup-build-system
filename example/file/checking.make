# Makefile
#
# Usage:
#
# ./bin/pbsmake -v -d example/file/checking.make target=T1			# Execute target T1
# ./bin/pbsmake -v -d example/file/checking.make target=dep-clean	# Dependency cleanup
#

VERBOSE	:= true
DEBUG	:= true
PHONY	:= all dist-clean

NAMESPACE := UUP\BuildSystem\Tests

T1 :
	Checking("C1")
T2 : T1
	Checking("C2")
T3 : T1
	Checking("C3")
T4 : T2
	Checking("C4")
T5 : T2 T3
	Checking("C5")
T6 : T4
	Checking("C6")
T7 : T5
	Checking("C7")
T8 : T5
	Checking("C8", 123, true)

clean: dist-clean
	Shell("rm -f *.bak")
all-clean: dist-clean
	Shell("rm -f *~")

dep-clean:
	Shell("rm -f build/*.last")
