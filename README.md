# console
## Installation
```
// Copy Console directory to new project
cd app/Console/Commands
cp -pr * /{{project}}/app/Console/Commands/

// Include RegisterCommands in the Kernel

// Rebuild Kernel with all commands in Console/Commands
php artisan register:commands
```
## Console Commands

* Comments.php
```
php artisan zulu:remove-comments {file} {--double-slash} {--single} {--block}
```

* Controller.php
```
// Make custom controller
php artisan zulu:make-controller {model}
```

* DatabaseSeeder.php
```
// Register all database seeders
php artisan zulu:update-seeder
```

* MigrateMysqlSchemaCommand.php
```
// @author Jim Pringle <pringlized@gmail.com>
// credits to @Christopher Pitt, @michaeljcalkins and Lee Zhen Yong whom this was forked from
// Reverse migrations from database
php artisan migrate:schema-mysql {database}
```

* Migrations.php
```
php artisan zulu:show-migrations {--write}

or

php artisan zulu:show-migrations > {file}
```

* Model.php
```
// Make new model from stub with options for controller, migration schema, and seeder.
php artisan zulu:make-model {model} {--c} {--m} {--s}
```

* PhpInfo.php
```
// Show PHP Info
php artisan phpinfo
```

* RegisterCommands.php
```
// Register commands in Console
php artisan register:commands
```

* Schema.php
```
// Make raw migration schema from stub for data fields/types
php artisan zulu:make-schema {model}
```
 
* Seeder.php
```
// Make raw seeder from stub for json data
php artisan zulu:make-seeder {model}
```

* UpdateModel.php
```
// Replace fill/cast code with fields from Database/Migrations/{{file}}
php artisan zulu:refactor-models {--fill} {--cast}
```

* Versions.php
```
// Show versions PHP, Laravel, MySQL
php artisan versions
```



