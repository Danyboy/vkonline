#!/usr/bin/python

import json, sys;

jsonFile = open(sys.argv[1], 'r')
values = json.load(jsonFile)
jsonFile.close()
for criteria in values['response']:
    for key, value in criteria.iteritems():
	if key == 'online_app' or key == 'hidden':
    	    print value,
    print ''

