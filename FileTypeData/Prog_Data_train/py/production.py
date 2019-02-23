import sys
sys.path.insert(0, '/var/www/gearopedia')

from instance.config import db_pass


DEBUG = False
SQLALCHEMY_DATABASE_URI= \
'postgresql://postgres:%s@localhost/gearopedia' % db_pass
