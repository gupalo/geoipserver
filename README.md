GeoIP Server
============

Provides IP data.

Use
===

### Build

Put GeoIP files to `var/data/`.

    make build
    make run

Go to http://127.0.0.1:8000

### Routes

#### /api/geoip

Provide `ips` and `apikey` in GET or POST params.

API Key can also be in header `X-Api-Key`.

`ips` - can be several IPs comma-separated (bulk).

Result (example is YAML but real result is JSON):

    ips:
        {ip1}:
            ip: {ip1}
            country_code: US
        {ip2}:
            ip: {ip2}
            country_code: UK

You can get all possible fields adding param `full=1` in URL.

#### /myip

Returns your IP as plaintext. Useful for commanline scripts ("How can I get my external IP?").

#### /myip/json

Get information about your current external IP.

Result is similar to `/api/geoip`.

You can also use `full=1` in URL to get all possible fields.

#### /healthcheck

Return plaintext "ok".

Config
======

### docker-compose.yaml

You can use own `docker-compose.yaml` file. Then:

* mount directory with GeoIP files to `/code/var/data/`
* you can use other port instead of 8000

See sample file in `docker/docker-compose.yaml`.

### Env vars

See default values in `.env` file. Override it via `docker-compose.yaml` if needed.

* `API_KEYS`: put real api keys comma-separated; **DON'T LEAVE DEFAULT VALUE (UNSECURE)**
* `WORKERS`: count of webserver workers; max performance if this count equals to available CPU cores
* `LISTEN`: IP and port to listen
