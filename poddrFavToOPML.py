import os
from xml.etree.ElementTree import Element, SubElement, Comment, ElementTree, tostring
from bs4 import BeautifulSoup
import json

homeDir = os.getenv('HOME')

favourites = homeDir + "/snap/poddr/current/.config/poddr/favourites.json"
subscriptions = homeDir + "/Downloads/subscriptions.xml"

try:
    f = open(favourites)
    if os.path.exists(subscriptions):
        os.remove(subscriptions)
        g = open(subscriptions, 'a')
    else:
        g = open(subscriptions, 'a')
    root = Element('opml')
    root.set('version', '1.0')
    head = SubElement(root, 'head')
    title = SubElement(head, 'title')
    title.text = 'Feeds Generated with PoddrToOPML'
    body = SubElement(root, 'body')
    outline = SubElement(body, 'outline')
    outline.set('text', 'feeds')

    with f:
        datastore = json.load(f)
        for i in datastore:
            podcast = SubElement(outline, 'outline',
                                  {'type': 'rss',
                                  'text': datastore[i]['title'],
                                  'title': datastore[i]['title'],
                                  'xmlUrl': datastore[i]['rss'],
                                  })
    ElementTree(root).write(g)
    g.close()
except IOError:
    print("Poddr favourites.json not found.")
finally:
    f.close()
