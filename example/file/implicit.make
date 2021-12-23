# Makefile

VERBOSE	:= true
DEBUG 	:= true

NAMESPACE := UUP\BuildSystem\Tests\Implicit

T1 :
T2 : T1
T3 : T1
T4 : T2
T5 : T2 T3
T6 : T4
T7 : T5
T8 : T5
