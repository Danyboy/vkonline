import json, sys;

jsonFile = open('history/friends-all', 'r')
values = json.load(jsonFile)
jsonFile.close()
for criteria in values['response']:
    for key, value in criteria.iteritems():
	if key == 'uid' or key == 'online':
    	    print value,
    print ''

