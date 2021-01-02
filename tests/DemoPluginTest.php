<?php declare(strict_types=1);
namespace JW\Utils;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use JW\DemoPlugin;

class DemoPluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    public function testPermalinks()
    {
        (new DemoPlugin())->setupPermalinks();
        self::assertNotFalse(has_action('init', [DemoPlugin::class, 'init']));
        self::assertNotFalse(has_action('template_include', [DemoPlugin::class, 'template_include']));
    }
}
