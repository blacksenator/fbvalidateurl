# fbvalidateurl

If you want to make a program available that access the interfaces of the FRITZ!Box and you want to ensure that the user has the freedom to enter his router address with or without schema, with host name or IP, then this routine could be useful ,
Not valid addresses lead to an exception. If successful, you get an array corresponding to [parse_url](https://www.php.net/manual/en/function.parse-url.php).

## Requirements

  * PHP 7.0
  * Composer (follow the installation guide at https://getcomposer.org/download/)

## Installation

You can install it through Composer:

    "require": {
        "blacksenator/fbvalidateurl": "dev-master#1.0"
    },

or

    git clone https://github.com/blacksenator/fbvalidateurl.git

## License
This script is released under MIT license.

## Authors
Copyright (c) 2019 Volker Püschel