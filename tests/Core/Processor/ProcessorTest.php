<?php

namespace Tests\Core\Processor;

use Core\Entity\Command;
use Core\Exception\ExecFailedException;
use Core\Processor\Processor;
use PHPUnit\Framework\TestCase;

/**
 * Class ProcessorTest
 */
class ProcessorTest extends TestCase
{

    /**
     * @throws \Exception
     */
    public function testExecuteSuccess()
    {
        $processor = new Processor();
        $command = new Command(Processor::IM_CONVERT_COMMAND);
        $command->addArgument('--version');
        $output = $processor->execute($command);
        $this->assertNotEmpty($output);
    }

    /**
     * @throws \Exception
     */
    public function testExecuteFail()
    {
        $this->expectException(ExecFailedException::class);
        $processor = new Processor();
        $command = new Command(Processor::IM_CONVERT_COMMAND);
        $command->addArgument('--invalid-option');
        $output = $processor->execute($command);
        $this->assertNotEmpty($output);
    }
}
