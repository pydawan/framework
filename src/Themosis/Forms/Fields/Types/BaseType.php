<?php

namespace Themosis\Forms\Fields\Types;

use Themosis\Forms\Contracts\FieldTypeInterface;
use Themosis\Html\HtmlBuilder;

abstract class BaseType implements \ArrayAccess, \Countable, FieldTypeInterface
{
    /**
     * @var HtmlBuilder
     */
    protected $html;

    /**
     * @var array
     */
    protected $attributes = [];

    public function __construct(HtmlBuilder $html)
    {
        $this->html = $html;
    }

    /**
     * Default field HTML structure.
     *
     * @return string
     */
    protected function build()
    {
        throw new \BadMethodCallException('A field must implement a default structure or view and return it.');
    }

    /**
     * Render the field to HTML.
     *
     * @param \Closure|null $callback
     *
     * @return string
     */
    public function toHTML(\Closure $callback = null)
    {
        if (is_callable($callback)) {
            return $callback($this);
        }

        return $this->build();
    }

    /**
     * Return field attributes as a string.
     *
     * @return string
     */
    public function attributes()
    {
        return count($this) ? ' '.$this->html->attributes($this->attributes) : '';
    }

    /**
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset An offset to check for.
     *
     * @return bool true on success or false on failure.
     *
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed Can return all value types.
     *
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->attributes[$offset]) ? $this->attributes[$offset] : null;
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     *
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset The offset to unset.
     *
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->attributes);
    }
}
