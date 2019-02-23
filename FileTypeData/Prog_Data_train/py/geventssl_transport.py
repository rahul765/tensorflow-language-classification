import warnings

from haigha.transports.gevent_transport import GeventTransport

try:
    from gevent import ssl, socket
except ImportError:
    warnings.warn('Failed to load gevent modules')

class GeventSSLTransport(GeventTransport):
    def __init__(self, *args, **kwargs):
        super(GeventSSLTransport, self).__init__(*args)
        self.ca_certs = kwargs.get('ca_certs', None)
        self.cert_reqs = kwargs.get('cert_reqs', ssl.CERT_REQUIRED)

    def connect(self, (host, port)):
        '''
        Connect using a host,port tuple
        '''
        def _sslwrapper(family, socktype, proto):
            sock = socket.socket(family, socktype, proto)
            return ssl.wrap_socket(sock, cert_reqs=self.cert_reqs, ca_certs=self.ca_certs)

        super(GeventTransport, self).connect((host, port), klass=_sslwrapper)
