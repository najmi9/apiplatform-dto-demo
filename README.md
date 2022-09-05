## Requirements:
- php >= 8.1
- composer
- symfony cli
- mysql server
- docker & docker-compose

I have installed PHP 7.4 on my host, but I will use a simple docker-compose file to use the PHP 8.1 and a simple database container to test with.
in the root directory put this file there:

```Dockerfile

```

```bash
mkdir dto-demo

cd dto-demo

touch docker-compose.yaml
## copy the code and .docker folder

USER_ID=$(id -u) GROUP_ID=$(id -g) docker-compose build

docker-compose up -d

docker exec -it php-container bash

composer init --name dto/demo --type project --author "username <email@gmail.com>" # click Enter to skip all questions

composer require symfony/webapp-pack #Install symfony dependencies
composer require symfony/flex  #Install symfony fex to get the folder structure  and the basic configuration
composer require symfony/runtime symfony/yaml symfony/dotenv

git init # Initialize a new git repository

```

Add this lines to the <span class="text-warning">composer.json</span>:
```json
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\Tests\\": "tests/"
        }
    },
```

and run the command:

```bash
composer dumpautoload
echo DATABASE_URL="mysql://root:root@db:3306/app?serverVersion-8&charset=utf8mb4" > env.local
symfony console doctrine:database:create --if-not-exists

```

```bash
composer require api
```

Create a PHP class <span class="danger">UserResetPasswordDto</span> in the <span class="success">DTO</span> namespace, and paste the following code:

```php
<?php

declare(strict_types=1);

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class UserResetPasswordDto
{
    #[Assert\Email]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private string $email;
}
```

```bash
symfony console make:user

symfony console make:migration

symfony console doctrine:migrations:migrate

symfony serve --port 80 -d

composer require orm-fixtures --dev
symfony console make:fixtures UserFixtures

symfony console doctrine:fixtures:load --no-interaction

curl --header "Content-Type: application/json" \
    --request POST \
    --data '{"email":"user@gmail.com"}' \
    http://localhost/api/users

```
