
### start project
For running this project in your local environment at first, you should run below command for creating docker containers.

`docker-compose -f docker-compose.yml -f docker-compose.local.yml up -d --build`

Then create .env file from .env.example file.

`cp .env.example .env`

After running the containers, run following commands for installing dependencies and linking storage:
```
docker-compose exec product_php composer install
docker-compose exec product_php php artisan storage:link
docker-compose exec product_php php artisan key:generate
```
Now you can see index page in http://localhost:8081.

run below command for building authentication client pages

```
npm install
npm run dev
```

For creating database and seeding fake data run below command:

`docker-compose exec product_php php artisan migrate --seed`

This command cause inserting a user as admin. This admin has below username and password:

- username = admin@product.test
- password = 12345678


For running unit tests you should run below command:

`docker-compose exec product_php vendor/bin/phpunit`

###EndPoints
This project has two main endpoints as below:
- Insert products from csv file (/admin/product)
- Get list of products with search, filter and pagination (/api/products)

### More descriptions
For search in different fields of product you should add search item to url parameters as below:

`search[field]=fieldValue&search[q]=value`

Also, for filtering:

`filters[field]=value` 

Sample of csv file for inserting new products have been placed in folder app/Constant/Document.
Also, You can find Postman json file and environment file in same folder. 
  

