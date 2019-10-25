#!/bin/sh

curl http://167.71.255.145:5984/oz-orcid/_design/export/_list/values/jsonl > backup.jsonl
