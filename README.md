# Instapro Coding Standard

The Instapro Coding Standard is a set of [PHP CS Fixer][] rules that we use in the Instapro projects.

## How to use

1. Make sure composer has access to this package by adding this repository to composer.json
   ```json
   "repositories": [
       {
           "type": "composer",
           "url": "https://gitlab.werkspot.com/api/v4/group/49/-/packages/composer/packages.json"
       }
   ]
   ```
2. Install the package as a dev requirement
   ```shell
   composer require --dev instapro/coding-standard
   ```
3. Add/update your `.php-cs-fixer.dist.php` configuration file to load the configuration from the package. Be sure to
   update the finder configuration to match the files you want to enforce the coding standard for. 

   ```php
   <?php
   
   declare(strict_types=1);
   
   use Instapro\CodingStandard\Load;
   
   return Load::configuration(
       PhpCsFixer\Finder::create()
           ->exclude('Files')
           ->in([
               __DIR__ . '/src',
               __DIR__ . '/tests',
           ])
           ->name('*.php'),
   );
   ```

### Troubleshooting

If for some reason your project doesn't have access to the package repository and your pipelines are complaining you need
to add the following things to the pipeline configuration

1. In the build part of the ci configuration add the following parameter to the docker build command
   ```
   --secret id=COMPOSER_AUTH,src=$COMPOSER_AUTH_FILE
   ```
2. In the `Dockerfile` add the following before the `RUN composer install` step
   ```
   --mount=type=secret,required=true,id=COMPOSER_AUTH,dst=/root/.composer/auth.json
   ```
   so it looks something like this:
   ```
   RUN --mount=type=secret,required=true,id=COMPOSER_AUTH,dst=/root/.composer/auth.json composer install --no-cache --optimize-autoloader
   ```

[PHP CS Fixer]: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
