# wishlist
Simple Laravel demo using the Svelte starter

# running the local development server
- requires docker to be installed
- cp .env.example .env
- docker compose up
- docker compose exec -it laravel sh -c 'php artisan migrate'
- docker compose exec -it laravel sh -c 'php artisan test'

# testing routes with curl

  1. Register a New User
  This command sends a POST request to create a new user. We use --cookie-jar to save the session cookie returned by Laravel.

    1 curl -i -X POST http://localhost:8000/api/register \
    2      -H "Content-Type: application/json" \
    3      -H "Accept: application/json" \
    4      --cookie-jar cookies.txt \
    5      -d '{
    6         "name": "John Doe",
    7         "email": "john@example.com",
    8         "password": "password",
    9         "password_confirmation": "password"
   10      }'
  Expected: 201 Created status and a JSON object of the new user.

  ---

  2. Login
  If the user already exists, use this command to authenticate. This will update your cookies.txt with the authenticated session.

   1 curl -i -X POST http://localhost:8000/api/login \
   2      -H "Content-Type: application/json" \
   3      -H "Accept: application/json" \
   4      --cookie-jar cookies.txt \
   5      -d '{
   6         "email": "john@example.com",
   7         "password": "password"
   8      }'
  Expected: 200 OK (or 204 No Content) and an authenticated session stored in cookies.txt.

  ---

  3. Access Protected Routes (e.g., User Profile)
  Now that you are logged in and have a session cookie, you can access the protected routes by passing the cookies.txt back to the
  server.

   1 curl -X GET http://localhost:8000/api/user \
   2      -H "Accept: application/json" \
   3      --cookie cookies.txt
  Expected: 200 OK and the JSON data for the authenticated user.

   1 curl -X GET http://localhost:8000/api/products \
   2      -H "Accept: application/json" \
   3      --cookie cookies.txt
  Expected: 200 OK and the JSON data for the authenticated user.
  ---

  Pro-Tip: Testing Validation Errors
  You can verify that Fortify is correctly returning JSON validation errors by sending an invalid request (e.g., missing a password):

   1 curl -i -X POST http://localhost:8000/api/login \
   2      -H "Content-Type: application/json" \
   3      -H "Accept: application/json" \
   4      -d '{"email": "john@example.com"}'
  Expected: 422 Unprocessable Content with a JSON object containing the validation errors.
