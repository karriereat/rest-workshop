Ressourcen
===

Eine Ressource ist ein Objekt mit einem Typ, 
Daten und Relationen zu anderen Ressourcen.

Beispiele
---

### Person
```
{
    "id": 1234   
    "name": "Mabel Pines"
    "age": 31,
    "company": 43
    "occupation": 32
}

```

### Firma
```
{
    "id": 43   
    "name": "Mystery Shack"
    "location": "Gravity Falls"
}

```


### Beruf
```
[
    {
        "id": 32 
        "name": "Abenteurer"
    },
    {
        "id": 12   
        "name": "Verkäufer"
    }
]

```



Negativbeispiele für resourcen
---


```
{
    "seconds-since-last-save": 123123
}
```


```
{
    "time-to-live": 12
}
```


Identifier 
---


Ressourcen haben immer einen unique Identifier.
Der Identifier muss nicht unbedingt ein Integer sein.

* article/5
* article/12
* company/karriere-at
* user/a3edf-234af-a7bdf-3f423


Alternativen zu JSON
---

* XML
* CSV
* HTML
* YAML
* ...