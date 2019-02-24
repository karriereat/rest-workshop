Error Codes
===

HTTP Status Codes sind of nicht ausreichend um aussagekräftige Fehlermeldungen zu erzeugen.
Ausserdem muss eine REST API nicht über HTTP ausgeliefert werden.

Regeln für Fehlermeldungen
---

- Lesbar für Menschen
- Lesbar für Maschienen
- Einheitlich über alle Endpoints

Beispiele
---

Eigene Codes definieren

- 01: invalid JSON
- 02: field missing: firstname
- 03: invalid email
- 04: user already exists
- 05: ....

Eigene Namespaces für Fehler (Wie Status Codes)

- 1001: invalid JSON
- 2002: field missing: firstname
- 2003: invalid email
- 2004: user already exists
- 3005: ....

Eigene Error Objecte

```
"errors": [
    {
        "code": 02
        "name": "field missing"
        "context": "firstname"
    },
    {
        "code": 02
        "name": "field missing"
        "context": "lastname"
    },
]
```