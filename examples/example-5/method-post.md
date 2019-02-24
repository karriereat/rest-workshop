Methode: POST
===

Mit POST werden Daten an den Server übermittelt. 
Post kann zum erzeugen neuer Ressourcen verwendet werden wenn keine ID bekannt ist.

### Regeln für POST:

- POST legt neue Ressourcen an
- POST Requests verändern keine existierenden Ressourcen
- Payload von Post wird im Body mitgeschickt
- Mehrere identische Requests erzeugen mehrere Ressourcen

### Beispiele

```
POST https://127.0.0.1/api/article
{
    "title": "Hello World!",
    "category": 5,
    "text": "..."
}

```

### Negativ Beispiele

```
POST https://127.0.0.1/api/article/5
{
    "title": "Hello World!",
    "category": 5,
    "text": "..."
}

```