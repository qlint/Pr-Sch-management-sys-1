<?php
/**
 * This file is part of BcBackgroundProcess.
 *
 * (c) 2013 Florian Eckerstorfer
 */

//namespace Bc\BackgroundProcess;

/**
 * BackgroundProcess
 *
 * Runs a process in the background.
 *
 * @package   BcBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 */
class BackgroundProcess
{
    /** @var string */
    private $command;

    /** @var integer */
    private $pid;

    /**
     * Constructor.
     *
     * @param string $command The command to execute
     */
    public function __construct($command=NULL)
    {
		if($command!=NULL)
       		$this->command = $command;
    }

    /**
     * Runs the command in a background process.
     *
     * @param string $outputFile File to write the output of the process to; defaults to /dev/null
     *
     * @return void
     */
    public function run($outputFile = '/dev/null')
    {
        $this->pid	=	shell_exec(sprintf(
            '%s > %s 2>&1 & echo $!' ,
            $this->command,
            $outputFile
        ));
		
		if($this->pid)
			return true;
		return false;
    }

    /**
     * Returns if the process is currently running.
     *
     * @return boolean TRUE if the process is running, FALSE if not.
     */
    public function isRunning($process_id)
    {
		try {
            $result = shell_exec(sprintf('ps %d', $process_id));
            if(count(preg_split("/\n/", $result)) > 2) {
                return true;
            }
        } catch(Exception $e) {}

        return false;
    }

    /**
     * Returns the ID of the process.
     *
     * @return integer The ID of the process
     */
    public function getPid()
    {
        return $this->pid;
    }
}
