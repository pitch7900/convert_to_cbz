# Convert to CBZ

Convert any CBR, CBR, PDF to a valid and clean CBZ
This is supposed to be used as a container

A valid token should be passed in the headers
Token is set in the docker-compose.yml file



## Installation

```bash
cd docker
docker-compose up -d --build
```

## Usage

### Status

```bash
curl --location --request GET 'http://localhost:1080/status.json' \
--header 'token: 123456789'
```

### Post a file to convert

```bash
curl --location --request POST 'http://localhost:1080/convert' \
--header 'token: 123456789' \
--form 'file=@"/pathtocbzfile.cbz"
```
