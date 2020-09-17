<?php

// rector.php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
	// get parameters
	$parameters = $containerConfigurator->parameters();

	// here we can define, what sets of rules will be applied
	$parameters->set(Option::SETS, [SetList::CODE_QUALITY]);

	$parameters->set(Option::AUTOLOAD_PATHS, [
		// autoload specific file
		realpath(getcwd()) . '/vendor/codeigniter4/codeigniter4/system/Test/bootstrap.php',
	]);

	// get services
	$services = $containerConfigurator->services();

	// register single rule
	$services->set(TypedPropertyRector::class);
};
