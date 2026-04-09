#!/bin/bash
set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
    -- 1. Setup Testing Database (Current logic)
    CREATE DATABASE testing;
    GRANT ALL PRIVILEGES ON DATABASE testing TO "$POSTGRES_USER";

    -- 2. Create dedicated Electric Sync User (New logic)
    -- We use double quotes for the username to handle any special characters
    CREATE USER "$ELECTRIC_USER" WITH PASSWORD '$ELECTRIC_PASSWORD';
    ALTER USER "$ELECTRIC_USER" WITH REPLICATION;

    -- 3. Grant Electric access to the main app database
    GRANT ALL PRIVILEGES ON DATABASE "$POSTGRES_DB" TO "$ELECTRIC_USER";

    -- 4. Grant Electric access to the testing database (Optional but recommended)
    GRANT ALL PRIVILEGES ON DATABASE testing TO "$ELECTRIC_USER";

    -- 5. Permission for the public schema
    -- This allows Electric to monitor tables created by Laravel migrations
    GRANT ALL ON SCHEMA public TO "$ELECTRIC_USER";

    -- 6. Ensure Electric can manage triggers on tables owned by the Laravel user
    ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO "$ELECTRIC_USER";
EOSQL
