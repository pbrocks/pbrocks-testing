# error-log.md

## Issue 1

### Message

```sh
phpcs: Unable to locate phpcs. Command failed: "/usr/local/bin/phpcs" --version env: php: No such file or directory
```

### Answer

https://stackoverflow.com/a/50078879/14827123

The easiest way is to use composer to install phpcs globally and symlink the binary into your path;

Assuming you have composer installed and are using osx or linux (if not, follow instructions from here: composer) then install phpcs globally: open your terminal and type:

```sh
composer global require "squizlabs/php_codesniffer=*"
```

You will then need to make sure phpcs is in your path. The easiest way is to symlink into /usr/local/bin. open your terminal and type:

```sh
sudo ln -s ~/.composer/vendor/bin/phpcs /usr/local/bin/phpcs
```
