# References

Create a RIS dump of all (structured) references cited, then import into MySQL database.

```
curl http://localhost:32769/oz-csl/_design/references/_list/values/ris > all.ris
```

```
php harvest_ris.php all.ris > all.sql
```
