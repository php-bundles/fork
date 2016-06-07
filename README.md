Symfony Fork Bundle
===================

[![SensioLabsInsight][sensiolabs-insight-image]][sensiolabs-insight-link]

[![Build Status][testing-image]][testing-link]
[![Scrutinizer Code Quality][scrutinizer-code-quality-image]][scrutinizer-code-quality-link]
[![Code Coverage][code-coverage-image]][code-coverage-link]
[![Total Downloads][downloads-image]][package-link]
[![Latest Stable Version][stable-image]][package-link]
[![License][license-image]][license-link]

Installation
------------

* Require the bundle with composer:

``` bash
composer require symfony-bundles/fork-bundle
```

* Enable the bundle in the kernel:

``` php
public function registerBundles()
{
    $bundles = [
        // ...
        new SymfonyBundles\ForkBundle\SymfonyBundlesForkBundle(),
        // ...
    ];
    ...
}
```

* Configure the ForkBundle in your config.yml (if needed).

Defaults configuration:

``` yml
sb_fork:
    class:
        fork: "SymfonyBundles\ForkBundle\Service\Fork"
        process: "SymfonyBundles\ForkBundle\Service\Process"
```

How to use (only cli-mode)
--------------------------

Gets the fork service:

``` php
$fork = $this->getContainer()->get('sb_fork');
// or `get('fork')`, fork - defaults alias for service
$fork = $this->getContainer()->get('fork');
```

Create a task that implements an interface `SymfonyBundles\ForkBundle\Service\TaskInterface`.
For example:

``` php
namespace AppBundle\Task;

use SymfonyBundles\ForkBundle\Service\TaskInterface;

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
$task = new \AppBundle\Task\DemoTask();

$fork->attach($task)->run(4)->wait(); // 4 - this is number of subprocesses
```

And another example:
``` php
$task1 = new \AppBundle\Task\DemoTask();
$task2 = new \AppBundle\Task\DemoTask();
$task3 = new \AppBundle\Task\DemoTask();

$fork->attach($task1)->attach($task2)->attach($task3)->run()->wait(); // defaults number of subprocesses is 8
```

If you call method `wait`, the current process (main) will wait while all child processes will be finished.

[package-link]: https://packagist.org/packages/symfony-bundles/fork-bundle
[license-link]: https://github.com/symfony-bundles/fork-bundle/blob/master/LICENSE
[license-image]: https://poser.pugx.org/symfony-bundles/fork-bundle/license
[testing-link]: https://travis-ci.org/symfony-bundles/fork-bundle
[testing-image]: https://travis-ci.org/symfony-bundles/fork-bundle.svg?branch=master
[stable-image]: https://poser.pugx.org/symfony-bundles/fork-bundle/v/stable
[downloads-image]: https://poser.pugx.org/symfony-bundles/fork-bundle/downloads
[sensiolabs-insight-link]: https://insight.sensiolabs.com/projects/d4b8a2dc-0b99-4111-aa3b-c7b4df469615
[sensiolabs-insight-image]: https://insight.sensiolabs.com/projects/d4b8a2dc-0b99-4111-aa3b-c7b4df469615/big.png
[code-coverage-link]: https://scrutinizer-ci.com/g/symfony-bundles/fork-bundle/?branch=master
[code-coverage-image]: https://scrutinizer-ci.com/g/symfony-bundles/fork-bundle/badges/coverage.png?b=master
[scrutinizer-code-quality-link]: https://scrutinizer-ci.com/g/symfony-bundles/fork-bundle/?branch=master
[scrutinizer-code-quality-image]: https://scrutinizer-ci.com/g/symfony-bundles/fork-bundle/badges/quality-score.png?b=master
