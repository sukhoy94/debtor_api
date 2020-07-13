## Ways to run unit tests

vendor/bin/phpunit --filter methodName className pathTofile.php
vendor/bin/phpunit --filter 'namespace\\directoryName\\className::methodName'

#### Test single class:

vendor/bin/phpunit tests/Feature/UserTest.php
vendor/bin/phpunit --filter  tests/Feature/UserTest.php
vendor/bin/phpunit --filter 'Tests\\Feature\\UserTest'
vendor/bin/phpunit --filter 'UserTest' 
#### Test single method:

 vendor/bin/phpunit --filter testExample 
 vendor/bin/phpunit --filter 'Tests\\Feature\\UserTest::testExample'
 vendor/bin/phpunit --filter testExample UserTest tests/Feature/UserTest.php

#### Run tests from all class within namespace:

vendor/bin/phpunit --filter 'Tests\\Feature'