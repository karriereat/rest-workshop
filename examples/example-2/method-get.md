Methode: GET
===

GET ist eine der vordefinierten HTTP Methoden.

Mit GET werden Daten von einem Webserver (Website, API) abgeholt. 

### Regeln für GET:

- Daten am Server werden nicht verändert
- Es sollte kein Payload (Body) an den Server geschickt werden
- Wiederholte Aufrufe der selben Ressource über GET liefern **immer** das selbe Ergebniss (idempotent)
- Endpoints dürfen einen Identifier (ID) haben 
- Alle notwendigen Information sind in der URI enthalten

### Beispiele

- GET https://127.0.0.1/api/article
- GET https://127.0.0.1/api/article/5
- GET https://127.0.0.1/api/article/5/comment
- GET https://127.0.0.1/api/comment?article=5
- GET https://127.0.0.1/api/comment?filter[article]=5