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

### Sqlite DB table creation

````sql
CREATE TABLE "logsdb" (
 "id" INTEGER,
 "file" TEXT,
 "tmp_name" TEXT,
 "size" INTEGER,
 "start" TIMESTAMP,
 "end" TIMESTAMP,
 "status" TEXT,
 "created_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 "updated_at" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 PRIMARY KEY("id" AUTOINCREMENT)
);
````

## Push on scaleway registry

````bash
docker login rg.fr-par.scw.cloud/ns-ebooks -u nologin

docker tag converter-service rg.fr-par.scw.cloud/ns-ebooks/converter-service:latest
docker push rg.fr-par.scw.cloud/ns-ebooks/converter-service:latest

````
