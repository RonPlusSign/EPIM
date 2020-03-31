# E.P.I.M.
## E-Commerce Platform ITIS Meucci. 

Web app of an e-commerce website, with user registation, login, admin and products management. 

---

### Folder Structure (self-explanatory)
 - Backend
 - Frontend
 - Database

---

### Enviroment set-up

The project integrates a docker enviroment. To launch it enter:
```bash
docker-compose -f "docker-compose.yml" up  --build
```

<br>

#### After the first launch you should also initialize the database. <br>

If the command doesn't work wait a few minutes to let docker finish initalize the containers.<br>
Launch the builddb script (the same script should also be valid for linux):

```cmd
.\builddb.ps1
```
(the same command is also used to rebuild the db from 0). 

<br>

Available services in the container:

 - Vue.js server: **`localhost:8080`**
 - phpMyAdmin: `pma.localhost:8080`
 - traefik dashboard: `localhost:8001`
