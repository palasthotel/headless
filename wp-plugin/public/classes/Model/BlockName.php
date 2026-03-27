<?php

namespace Palasthotel\WordPress\Headless\Model;

/**
 * Represents a Gutenberg block name composed of a namespace and a block ID.
 *
 * Stringifies to the standard "namespace/id" format used by WordPress block names.
 */
class BlockName {

	/**
	 * @var string The block namespace (e.g. "core").
	 */
	private string $namespace;

	/**
	 * @var string The block identifier within the namespace (e.g. "paragraph").
	 */
	private string $id;

	/**
	 * @param string $namespace The block namespace.
	 * @param string $id        The block identifier.
	 */
	public function __construct(string $namespace, string $id) {
		$this->namespace = $namespace;
		$this->id = $id;
	}

	/**
	 * Named constructor for creating a BlockName instance.
	 *
	 * @param string $namespace The block namespace.
	 * @param string $id        The block identifier.
	 * @return static A new BlockName instance.
	 */
	public static function build(string $namespace, string $id){
		return new static($namespace, $id);
	}

	/**
	 * Returns the full block name in "namespace/id" format.
	 *
	 * @return string The block name string.
	 */
	public function __toString(): string {
		return $this->namespace."/".$this->id;
	}
}