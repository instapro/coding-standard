# Instapro Coding Standard

The Instapro Coding Standard is a set of [PHP CS Fixer][] rules that we use in the Instapro projects.

## How to use

1. Install the package as a dev requirement
   ```shell
   composer require --dev instapro/coding-standard
   ```
2. Add/update your `.php-cs-fixer.dist.php` configuration file to load the configuration from the package. Be sure to
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

[PHP CS Fixer]: https://github.com/PHP-CS-Fixer/PHP-CS-Fixer
