
import urllib

from google.appengine.ext import blobstore, db
from models import SessionData
from google.appengine.ext.webapp import blobstore_handlers

import webapp2

class ServeHandler(blobstore_handlers.BlobstoreDownloadHandler):
  def get(self, resource):
    resource = str(urllib.unquote(resource))
    blob_info = blobstore.BlobInfo.get(resource)
    query = db.GqlQuery("SELECT * FROM SessionData WHERE blob_store_key = '%s'" % resource)
    for session in query:
        filename = session.filename
    self.send_blob(blob_info, save_as = filename)


app = webapp2.WSGIApplication(
          [
           ('/serve/([^/]+)?', ServeHandler),

], debug=True)
