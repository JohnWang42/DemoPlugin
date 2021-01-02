<?php declare(strict_types=1);
require_once __DIR__."/../src/DemoPlugin.php";
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use JW\DemoPlugin;

class DemoPluginTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        Monkey\setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    /**
     * Tests that the permalink action and filter have been properly included
     */
    public function testPermalink()
    {
        $plugin = DemoPlugin::getInstance();
        $plugin->setupPermalinks();
        self::assertNotFalse(has_action('init', [$plugin, 'demoUsers']));
        self::assertNotFalse(has_filter('template_include', [$plugin, 'displayUsers']));
    }

    /**
     * Tests that the AJAX hooks are in place
     */
    public function testAjaxHooks()
    {
        $plugin = DemoPlugin::getInstance();
        $plugin->setupAjaxHooks();
        self::assertNotFalse(has_action('wp_ajax_jwGetUsers', [$plugin, 'ajaxGetUsers']));
        self::assertNotFalse(has_action('wp_ajax_nopriv_jwGetUsers', [$plugin, 'ajaxGetUsers']));
        self::assertNotFalse(has_action('wp_ajax_jwGetUser', [$plugin, 'ajaxGetUser']));
        self::assertNotFalse(has_action('wp_ajax_nopriv_jwGetUser', [$plugin, 'ajaxGetUser']));
    }
}
