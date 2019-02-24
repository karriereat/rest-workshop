Methode: PUT
===

Mit PUT werden bestehende Ressourcen verändert, oder Ressourcen erzeugt, bei denen der Identifier bekannt ist.


### Regeln für PUT:

- ID von Ressource ist bekannt
- Payload von PUT wird im Body mitgeschickt
- Mehrere identische Requests verändern die selbe Ressource (idempotent)

### Beispiele

```
PUT https://127.0.0.1/api/article/5
{
    "title": "Hello World!",
    "category": 5,
    "text": "..."
}

```

### Negativ Beispiele

```
PUT https://127.0.0.1/api/article/
{
    "title": "Hello World!",
    "category": 5,
    "text": "..."
}

```