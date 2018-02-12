SymfonyBundles Fork Library
===========================

[![SensioLabsInsight][sensiolabs-insight-image]][sensiolabs-insight-link]

[![Build Status][testing-image]][testing-link]
[![Scrutinizer Code Quality][scrutinizer-code-quality-image]][scrutinizer-code-quality-link]
[![Code Coverage][code-coverage-image]][code-coverage-link]
[![Total Downloads][downloads-image]][package-link]
[![Latest Stable Version][stable-image]][package-link]
[![License][license-image]][license-link]

Installation
------------

Install the library with composer:

``` bash
composer require symfony-bundles/fork
```

How to use (only cli-mode)
--------------------------

Create the fork service:

``` php
use SymfonyBundles\Fork;

$fork = new Fork\Fork();
```

Create a task that implements an interface `SymfonyBundles\Fork\TaskInterface`.
For example:

``` php
namespace AppBundle\Task;

use SymfonyBundles\Fork\TaskInterface;

class DemoTask implements TaskInterface
{
    public function execute()
    {
        echo "Hello World!\n";
    }
}
```

Now that the task is created, you can execute her in a plurality processes:

``` php
use AppBundle\Task\DemoTask;

$task = new DemoTask();

$fork->attach($task)->run(4)->wait(); // 4 - this is number of subprocesses
```

And another example:
``` php
$task1 = new DemoTask();
$task2 = new DemoTask();
$task3 = new DemoTask();

$fork->attach($task1)->attach($task2)->attach($task3);
$fork->run(); // by default, the optimal number of subprocesses will be determined
$fork->wait();
```

If you call method `wait`, the current process (main) will wait while all child processes will be finished.

[package-link]: https://packagist.org/packages/symfony-bundles/fork
[license-link]: https://github.com/symfony-bundles/fork/blob/master/LICENSE
[license-image]: https://poser.pugx.org/symfony-bundles/fork/license
[testing-link]: https://travis-ci.org/symfony-bundles/fork
[testing-image]: https://travis-ci.org/symfony-bundles/fork.svg?branch=master
[stable-image]: https://poser.pugx.org/symfony-bundles/fork/v/stable
[downloads-image]: https://poser.pugx.org/symfony-bundles/fork/downloads
[sensiolabs-insight-link]: https://insight.sensiolabs.com/projects/83639a9c-881b-4738-b3e9-ea304600c900
[sensiolabs-insight-image]: https://insight.sensiolabs.com/projects/83639a9c-881b-4738-b3e9-ea304600c900/big.png
[code-coverage-link]: https://scrutinizer-ci.com/g/symfony-bundles/fork/?branch=master
[code-coverage-image]: https://scrutinizer-ci.com/g/symfony-bundles/fork/badges/coverage.png?b=master
[scrutinizer-code-quality-link]: https://scrutinizer-ci.com/g/symfony-bundles/fork/?branch=master
[scrutinizer-code-quality-image]: https://scrutinizer-ci.com/g/symfony-bundles/fork/badges/quality-score.png?b=master
