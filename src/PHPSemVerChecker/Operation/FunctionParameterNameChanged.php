<?php

namespace PHPSemVerChecker\Operation;

use PhpParser\Node\Stmt\Function_;
use PHPSemVerChecker\SemanticVersioning\Level;

class FunctionParameterNameChanged extends Operation {
	/**
	 * @var string
	 */
	protected $code = 'V067';
	/**
	 * @var int
	 */
	protected $level = Level::PATCH;
	/**
	 * @var string
	 */
	protected $reason = 'Function parameter name changed.';
	/**
	 * @var string
	 */
	protected $fileBefore;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionBefore;
	/**
	 * @var string
	 */
	protected $fileAfter;
	/**
	 * @var \PhpParser\Node\Stmt\Function_
	 */
	protected $functionAfter;

	/**
	 * @param string                         $fileBefore
	 * @param \PhpParser\Node\Stmt\Function_ $functionBefore
	 * @param string                         $fileAfter
	 * @param \PhpParser\Node\Stmt\Function_ $functionAfter
	 */
	public function __construct($fileBefore, Function_ $functionBefore, $fileAfter, Function_ $functionAfter)
	{
		$this->fileBefore = $fileBefore;
		$this->functionBefore = $functionBefore;
		$this->fileAfter = $fileAfter;
		$this->functionAfter = $functionAfter;
	}

	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->fileBefore;
	}

	/**
	 * @return int
	 */
	public function getLine()
	{
		return $this->functionAfter->getLine();
	}

	/**
	 * @return string
	 */
	public function getTarget()
	{
		$fqfn = $this->functionAfter->name;
		if ($this->functionAfter->namespacedName) {
			$fqfn = $this->functionAfter->namespacedName->toString() . '::' . $this->functionAfter->name;
		}
		return $fqfn;
	}
}