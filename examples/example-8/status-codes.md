Status Codes
===

- **2xx: Success** Request war erfolgreich
- **3xx: Redirection**	Die angefragte Ressouce ist woanders verfügbar.
- **4xx: Client Error**	Client hat etwas falsch gemacht
- **5xx: Server Error**	Probleme am Server

Gebräuchliche Codes
---

#### GET 

- **200: OK** wenn Ressource gefunden wurde und alles OK ist
- **404: Not Found** wenn Ressource nicht gefunden wurde

#### GET ALL

- **200: OK** auch wenn eine leere Liste zurück geschickt wird

#### POST

- **201: Created** Wenn Ressource erfolgreich angelegt wurde. Enthält oft einen Location Header mit Adresse zur neuen Ressource 
- **400: Bad Request** Wenn Daten nicht gelesen werden konnten, Daten fehlen oder falsche Angaben gemacht wurden 

#### PUT

- **200: OK** Wenn Ressource erfolgreich geändert wurde
- **204: No Content** Wenn Resource geändert wurde aber keine Rückgabe erfolgt
- **400: Bad Request** Wenn Daten nicht gelesen werden konnten, Daten fehlen oder falsche Angaben gemacht wurden 
- **404: Not Found** wenn Ressource nicht gefunden wurde

#### DELETE

- **204: No Content** Wenn Ressource erfolgreich gelöscht wurde
- **404: Not Found** wenn Ressource nicht gefunden wurde

#### GET, PUT, POST, DELETE, ...

- **202: Accepted** Wenn eine Anfrage richtig war, aber nicht sofort verarbeitet wird (Queueing)
- **401: Unauthorized** bei geschützten Ressourcen wenn Client nicht authenticated ist (Login)
- **403: Forbidden** bei geschützten Ressourcen die für authenticated Client nicht freigegeben sind (authorized)
- **405: Method Not Allowed** Wenn die Request Methode für eine Ressource nicht erlaubt ist
- **429: Too Many Requests** Wenn zuviele Anfragen innerhalb einer festgelegten Zeit gemacht wurden
- **500: Internal Server Error** Wenn der Server Probleme hat oder die API Bugs hat
