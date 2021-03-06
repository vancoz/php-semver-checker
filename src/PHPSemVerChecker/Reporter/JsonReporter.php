<?php

namespace PHPSemVerChecker\Reporter;

use PHPSemVerChecker\Report\Report;
use PHPSemVerChecker\SemanticVersioning\Level;
use PHPSemVerChecker\Wrapper\Filesystem;

class JsonReporter
{
	/**
	 * @var \PHPSemVerChecker\Report\Report
	 */
	protected $report;
	/**
	 * @var string
	 */
	protected $path;
	/**
	 * @var \PHPSemVerChecker\Wrapper\Filesystem
	 */
	protected $filesystem;

	/**
	 * @param \PHPSemVerChecker\Report\Report      $report
	 * @param string                               $path
	 * @param \PHPSemVerChecker\Wrapper\Filesystem $filesystem
	 */
	public function __construct(Report $report, $path, Filesystem $filesystem = null)
	{
		$this->report = $report;
		$this->path = $path;
		$this->filesystem = $filesystem ?: new Filesystem();
	}

	public function output()
	{
		$output = [];
		$output['level'] = Level::toString($this->report->getSuggestedLevel());
		$output['changes'] = [];

		$contexts = [
			'class',
			'function',
			'interface',
			'trait',
		];

		$differences = $this->report->getDifferences();
		foreach ($contexts as $context) {
			foreach (Level::asList('desc') as $level) {
				$reportForLevel = $differences[$context][$level];
				/** @var \PHPSemVerChecker\Operation\Operation $operation */
				foreach ($reportForLevel as $operation) {
					$output['changes'][$context][] = [
						'level' => Level::toString($level),
						'location' => $operation->getLocation(),
						'line' => $operation->getLine(),
						'target' => $operation->getTarget(),
						'reason' => $operation->getReason(),
						'code' => $operation->getCode(),
					];
				}
			}
		}

		$this->filesystem->write($this->path, json_encode($output, JSON_PRETTY_PRINT));
	}
}
