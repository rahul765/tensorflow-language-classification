""" example wsgi file with virtualenv"""
import site
site.addsitedir('/home/remote/ipydra')

from ipydra import create_app
application = create_app()
