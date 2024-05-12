<?php

namespace Valet\Tests\Unit;

use Mockery;
use PHPUnit\Framework\MockObject\MockObject;
use Valet\CommandLine;
use Valet\Contracts\PackageManager;
use Valet\Contracts\ServiceManager;
use Valet\ValetRedis;
use PHPUnit\Framework\TestCase;

class ValetRedisTest extends TestCase
{
    private PackageManager|MockObject $packageManager;
    private ServiceManager|MockObject $serviceManager;
    private CommandLine|MockObject $commandLine;
    private ValetRedis $redis;

    public function setUp(): void
    {
        parent::setUp();

        $this->packageManager = Mockery::mock(PackageManager::class);
        $this->packageManager->redisPackageName = 'redis-server';
        $this->serviceManager = Mockery::mock(ServiceManager::class);
        $this->commandLine = Mockery::mock(CommandLine::class);

        $this->redis = new ValetRedis(
            $this->packageManager,
            $this->serviceManager,
            $this->commandLine
        );
    }

    /**
     * @test
     */
    public function itWillInstallSuccessfully()
    {
        $this->packageManager
            ->shouldReceive('ensureInstalled')
            ->once()
            ->with('redis-server');

        $this->serviceManager
            ->shouldReceive('enable')
            ->once()
            ->with('redis-server');

        $this->redis->install();
    }

    /**
     * @test
     */
    public function itWillValidateIfPackageIsInstalled()
    {
        $this->packageManager
            ->shouldReceive('installed')
            ->once()
            ->with('redis-server')
            ->andReturnTrue();

        $isInstalled = $this->redis->installed();
        $this->assertTrue($isInstalled);
    }

    /**
     * @test
     */
    public function itWillRestartServiceSuccessfully()
    {
        $this->serviceManager
            ->shouldReceive('restart')
            ->once()
            ->with('redis-server');

        $this->redis->restart();
    }

    /**
     * @test
     */
    public function itWillStopServiceSuccessfully()
    {
        $this->serviceManager
            ->shouldReceive('stop')
            ->once()
            ->with('redis-server');

        $this->redis->stop();
    }

    /**
     * @test
     */
    public function itWillUninstallServiceSuccessfully()
    {
        $this->serviceManager
            ->shouldReceive('stop')
            ->once()
            ->with('redis-server');

        $this->redis->uninstall();
    }
}
