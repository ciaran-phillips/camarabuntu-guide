#!/usr/bin/python

import os
import sys

str = sys.argv[1];
str = str.replace("%20"," ")
str = str + "&"
strArray = str.split("runapp:")
str2 = strArray[1]
os.system(str2)
