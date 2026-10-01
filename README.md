# Backend

Vanilla PHP 8.1 + GraphQL. See the root README for setup.

```
public/index.php            bootstrap: autoload, config, CORS, routes
src/Config/Config.php       .env handling
src/Router/Router.php       tiny method+path router
src/Database/Database.php   PDO connection
src/Models/                 domain models (abstract classes + concrete types + factories)
src/Repositories/           SQL access, returns models
src/GraphQL/Types/          GraphQL object and input types
src/GraphQL/Resolvers/      one resolver class per concern
src/GraphQL/Schema/         schema assembly
src/GraphQL/Controller/     request entry point
database/schema.sql         MySQL 5.6 compatible schema
scripts/import.php          loads the provided data.json
```

Deployment note: point the web server document root at `backend/public`. If that
is impossible on shared hosting, put `public/` contents at the web root and adjust
the `require` path for `vendor/autoload.php`. `Router::normalize()` already
tolerates being served from a sub-directory.
