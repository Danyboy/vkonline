import json, sys;
#import argparse

#parser = argparse.ArgumentParser(description='Process some integers.')
#parser.add_option("-f", "--file", dest="filename",
#                  help="get json from FILE", const="jsonURL")

print sys.argv

jsonFile = open(jsonURL, 'r')
values = json.load(jsonFile)
jsonFile.close()
for criteria in values['response']:
    for key, value in criteria.iteritems():
	if key == 'uid' or key == 'online':
    	    print value,
    print ''

