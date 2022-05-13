<?php

namespace DigipolisGent\Tests\Robo\Task\Package;

use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use PHPUnit\Framework\TestCase;
use Robo\Collection\CollectionBuilder;
use Robo\Common\CommandArguments;
use Robo\Contract\ConfigAwareInterface;
use Robo\Robo;
use Robo\TaskAccessor;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;

class ThemeCompileTest extends TestCase implements ContainerAwareInterface, ConfigAwareInterface
{

    use \DigipolisGent\Robo\Task\Package\Tasks;
    use TaskAccessor;
    use ContainerAwareTrait;
    use CommandArguments;
    use \Robo\Task\Base\Tasks;
    use \Robo\Common\ConfigAwareTrait;

    protected $themePath;

    /**
     * Set up the Robo container so that we can create tasks in our tests.
     */
    public function setUp(): void
    {
        $container = Robo::createDefaultContainer();
        $this->setContainer($container);
        $this->setConfig(Robo::config());
        $this->themePath = realpath(__DIR__ . '/../testfiles/testtheme');
    }

    public function tearDown(): void
    {
        // Manual cleanup.
        $files = [
            '/.bundle',
            '/hello_gulp.txt',
            '/hello_grunt.txt',
            '/libraries',
            '/node_modules',
            '/vendor',
            '/package-lock.json',
        ];
        foreach ($files as $remove) {
            exec('rm -rf ' . $this->themePath . $remove);
        }
        if (file_exists($this->themePath . '/yarn.backup.lock')) {
            // Restore yarn.lock
            rename($this->themePath . '/yarn.backup.lock', $this->themePath . '/yarn.lock');
        }
    }

    /**
     * Scaffold the collection builder.
     *
     * @return \Robo\Collection\CollectionBuilder
     *   The collection builder.
     */
    public function collectionBuilder()
    {
        $emptyRobofile = new \Robo\Tasks;
        return CollectionBuilder::create($this->getContainer(), $emptyRobofile);
    }

    public function testRunNpm()
    {
        // Rename yarn.lock so npm is triggered and not yarn.
        rename($this->themePath . '/yarn.lock', $this->themePath . '/yarn.backup.lock');
        $this->runTask();

        // Test if package-lock.json is created, meaning npm was used, not yarn.
        $this->assertFileExists($this->themePath . '/package-lock.json');
        $this->assertFileDoesNotExist($this->themePath . '/yarn.lock');
    }

    public function testRunYarn()
    {
        $this->runTask();

        // Test absence of package-lock.json, meaning npm was used, not yarn.
        $this->assertFileExists($this->themePath . '/yarn.lock');
        $this->assertFileDoesNotExist($this->themePath . '/package-lock.json');
    }

    protected function runTask()
    {
        $result = $this->taskThemeCompile($this->themePath, 'build')->run();

        // Assert response.
        $this->assertEquals('', $result->getMessage());
        $this->assertEquals(0, $result->getExitCode());

        // Assert bower install ran.
        $this->assertFileExists($this->themePath . '/vendor/bundle');

        // Assert npm/yarn install ran.
        $this->assertFileExists($this->themePath . '/node_modules');

        // Assert bower install ran.
        $this->assertFileExists($this->themePath . '/libraries');

        // Assert grunt build ran.
        $this->assertFileExists($this->themePath . '/hello_grunt.txt');

        // Assert gulp build ran.
        $this->assertFileExists($this->themePath . '/hello_gulp.txt');
    }
}
