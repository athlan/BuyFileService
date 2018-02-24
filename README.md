## buy-file-service

## About

Microservice allows to purchase products like ebooks, do the payment and generate download url.

Written in php, simple Domain, Port and Adaptrs architecture, delivered by Silex framework.

## Installation

1. Checkout repository.
2. Run `composer intstall`.
3. Execute sql files included `/docs/sql` on MySQL database.
4. Point your *public_html* to the `/web/app.php` file.
5. Copy sample config file `/app/config/params.php.diff` to `/app/config/params.php`.
    1. Configure database connection
    2. Payment gateway settings
    3. Define products (explained below)

## Configuring products

1. Copy sample file `/app/config/params.php.diff` to `/app/config/params.php`
2. Define new product in `product.repository.data` section using template. Array key is a `productId`.
    1. Upload the file under `/files` directory and point to the file in `filestream.path` on yor product configuration.
    2. Create a folder `/templates/product/productId` based on `sample` replacing `productId` and customize mail contents.

## Configuring payment gatway

### PayU

Customize gateway setting in `/app/config/params.php` under `payment.gateway.payu` settings key.
