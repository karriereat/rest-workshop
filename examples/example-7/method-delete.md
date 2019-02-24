Methode: DELETE
===

Mit DELETE werden Daten am Server gelöscht. DELETE kann verwendet werden wenn 
eine ID bekannt ist.

### Regeln für DELETE:

- DELETE hat keinen Payload
- Alle notwendigen Information sind in der URI enthalten
- Es sollte kein Payload (Body) an den Server geschickt werden


### Beispiele

```
DELETE https://127.0.0.1/api/article/5
```

### Negativ Beispiele

```
DELETE https://127.0.0.1/api/article
{
    "id": "5"
}

```

```
POST https://127.0.0.1/api/article/5/delete

```