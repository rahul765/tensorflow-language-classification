package com.lucasia.tstf.jester.entity;

import java.io.IOException;
import java.io.InputStream;
import java.net.URI;

/**
 * User: lucasia
 */
public class URIContent implements Content<InputStream> {
    private URI uri;
    private InputStream inputStream;

    public URIContent(URI uri) throws IOException {
        this.uri = uri;
        this.inputStream = uri.toURL().openStream();
    }

    public URIContent(URI uri, InputStream inputStream) {
        this.uri = uri;
        this.inputStream = inputStream;
    }

    @Override
    public URI getURI() {
        return uri;
    }

    @Override
    public InputStream getContent() {
        return inputStream;
    }

    @Override
    public InputStream getContentStream() {
        return inputStream;
    }
}
