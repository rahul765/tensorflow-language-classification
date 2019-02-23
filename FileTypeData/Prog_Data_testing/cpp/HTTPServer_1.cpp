#import "HTTPServer.h"

#import <string.h>

#define STR_EQ(s) len == sizeof(s) - 1 && strncmp(str, s, sizeof(s) - 1) == 0
#define ERROR(code) error_code = code; return false;

static char CRLF[] = "\r\n";
static bool is_lws(char c) { return c == ' ' || c == '\t'; }
static bool is_numeral(char c) { return c >= '0' && c <= '9'; }

HTTPRequest::HTTPRequest(EthernetClient *aClient) :
    client(aClient),
    error_code(HTTP_NO_RESPONSE),
    method(HTTP_UNKNOWN_METHOD),
    content_length(0) {
  read_method() && read_url() && read_protocol() && read_headers();
}

#define METHOD(method_str, method_val) \
  if (STR_EQ(method_str)) { method = method_val; return true; }

boolean HTTPRequest::read_method() {
  char str[5]; int len = client->readBytesUntil(' ', str, sizeof(str));
  METHOD("GET", HTTP_GET)
  METHOD("POST", HTTP_POST)
  ERROR(HTTP_METHOD_NOT_ALLOWED)
}

boolean HTTPRequest::read_url() {
  // For now 404 on anything other than "/".
  int len = client->readBytesUntil(' ', url, sizeof(url));
  if (len == 5) { ERROR(HTTP_REQUEST_URI_TOO_LARGE); }
  url[len] = '\0';
  return true;
}

boolean HTTPRequest::read_protocol() {
  client->find(CRLF); // TODO(paulsowden) Verify protocol.
  return true;
}

boolean HTTPRequest::read_headers() {
  while (true) {
    // Just assume \r is followed by \n and return.
    if (client->peek() == '\r') { client->read(); client->read(); return true; }

    // Read the header name.
    char str[15]; int len = client->readBytesUntil(':', str, sizeof(str));
    while (is_lws(client->peek())) { client->read(); }

    if (STR_EQ("Content-length")) {
      char length[6];
      int i = 0;
      while (is_numeral(client->peek()) && i < sizeof(length) - 1) {
        length[i++] = client->read();
      }
      length[i] = '\0';
      content_length = atoi(length);
    }

    // Consume header continutation lines.
    client->find(CRLF);
    while (is_lws(client->peek())) { client->find(CRLF); }
  }
}


HTTPEntity::HTTPEntity(HTTPRequest *aRequest) :
  request(aRequest),
  bytes_read(0) {
}

int HTTPEntity::available() {
  return min(pending(), request->client->available());
}

int HTTPEntity::pending() {
  return request->content_length - bytes_read;
}

int HTTPEntity::peek() {
  // TODO(paulsowden)
  return -1;
}

int HTTPEntity::read() {
  while (request->client->connected() && pending()) {
    if (request->client->available()) {
      bytes_read++;
      return request->client->read();
    }
  }
  return -1;
}

void HTTPEntity::flush() {
  request->client->flush();
}

size_t HTTPEntity::write(uint8_t val) {
  return 0;
}

