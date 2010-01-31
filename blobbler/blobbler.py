#!/usr/bin/python
import lightblue, re, os, ConfigParser, urllib2, time
from xml.etree.ElementTree import fromstring

config = ConfigParser.ConfigParser()
config.read(('blobbler.conf', '/etc/blobbler.conf'))

# Load the static mac map
response = urllib2.urlopen(config.get('blobbler', 'macserver'))
file = response.read()
macs = file.split("\n")

user = config.get('blobbler', 'user')
wsurl = "http://ws.audioscrobbler.com/1.0/user/%s/recenttracks.rss" % user

print "Blobbler running, %d Macs known, waiting for %s to scrobble something..." % (len(macs)-1, user)

oldTime = 0

while True:

    response = urllib2.urlopen(wsurl)
    data = response.read()

    currentTime = 0

    tree = fromstring(data)
    for elem in tree.getiterator():
        if elem.tag == 'pubDate':
            currentTime = time.mktime(time.strptime(elem.text, '%a, %d %b %Y %H:%M:%S +0000'))
            break

    if oldTime == 0 and currentTime != 0:
        oldTime = currentTime
        continue

    if (currentTime != 0 and currentTime != oldTime):
        print "%s scrobbled something, looking for devices to scrobble to..." % user;

        oldTime = currentTime

        # Find devices we know the mac of
        foundMacs = []
        for mac in macs:
            try:
                name = lightblue.finddevicename(mac, False)
                print "Found %s" % mac
                foundMacs.append(mac)
            except:
                pass

        if foundMacs:
            macString = ','.join(foundMacs)
            scrobUrl = config.get('blobbler', 'server') + '?user=' + user + '&macs=' + macString
            print "Scrobbling: %s" % scrobUrl
            urllib2.urlopen(scrobUrl)

    time.sleep(60)
