Request/Response Objekt
===

* Alle Ressourcen identisch
* Leicht zu verstehen
* Dumme Clients
* Programmieraufwand überschaubar
* Einfache Wartung

Beispiel
===

Einheitliche Response
```json
{
  "data": {},
  "errors": {},
  "meta": {}
}
```

Einheitliche Ressource

```json
{
  "id": 12,
  "attributes": {
    "firstname": "Stan",
    "lastname": "Pines"
  },
  "relationships": {
    "company": "/company/23",
    "occupation": "/occupation/53"
  }
}
```

Zusammen

```json
{
  "data": {
      "id": 12,
      "attributes": {
        "firstname": "Stan",
        "lastname": "Pines"
      },
      "relationships": {
        "company": "/company/23",
        "occupation": "/occupation/53"
      }
  },
  "errors": {},
  "meta": {}
}


```
Collections

```json
{
  "data": [
    {
      "id": 12,
        "attributes": {
          "firstname": "Stan",
          "lastname": "Pines"
        },
        "relationships": {
          "company": "/company/23",
          "occupation": "/occupation/53"
        }
      },
      {
        "id": 13,
        "attributes": {
          "firstname": "Dipper",
          "lastname": "Pines"
        },
        "relationships": {
          "company": "/company/23",
          "occupation": "/occupation/32"
        }
      }
  ],
  "errors": {},
  "meta": {
    "page": 1,
    "total": 34
  }
}

```