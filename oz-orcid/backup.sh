#!/bin/sh

curl http://localhost:32775/oz-orcid/_design/export/_list/values/jsonl > backup.jsonl
