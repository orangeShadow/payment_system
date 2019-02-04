Report:

GET: /api/v1/payments-report
params : name*, format* = html|csv|xml ,[[start_dt], [end_dt]] 

Add Rate

/api/v1/add-rate
POST: params: currency*, rate*, date*

Add User

POST: /api/v1/add-rate
params: name*, country*, city*, currency*

Add Balance

POST: /api/v1/add-balance
params: name*, country*, city*, currency*

Send money

POST: /api/v1/remittance
params: (int)user_from*, (int)user_to*, currency, amount 


currency - USD, EUR, RUB, CHR ...


For compile interface run:

`npm install`

`npm run dev`

For generate fake data run: 

`php artisan payments:fake` 
