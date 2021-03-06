# Makefile

CONVERSION	:= production

VERBOSE	:= true
DEBUG	:= true
PHONY	:= all dist-clean

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
	Target("T8", 123, true)

clean: dist-clean
	Shell("rm -f *.bak")
all-clean: dist-clean
	Shell("rm -f *~")
