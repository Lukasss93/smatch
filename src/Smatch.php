<?php

namespace Lukasss93\Smatch;

use Closure;
use Lukasss93\Smatch\Exceptions\UnhandledSmatchException;

class Smatch
{
    /** @var mixed */
    protected $source;

    /** @var array */
    protected $cases;

    /** @var mixed */
    protected $default;

    protected function __construct($source)
    {
        $this->source = $source;
        $this->cases = [];
        $this->default = null;
    }

    /**
     * @param mixed $value
     * @return static
     */
    public static function source($value): self
    {
        return new self($value);
    }

    /**
     * @param mixed $condition
     * @param scalar|Closure $value
     * @return $this
     */
    public function case($condition, $value): self
    {
        if (!is_array($condition)) {
            $condition = [$condition];
        }

        foreach ($condition as $cond) {
            $this->cases[] = [$cond, $value];
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function fallback($value): self
    {
        $this->default = $value;

        return $this;
    }

    /**
     * @return mixed
     * @throws UnhandledSmatchException
     */
    public function get()
    {
        foreach ($this->cases as [$condition, $value]) {
            if (is_bool($condition)) {
                if ($condition) {
                    return ($value instanceof Closure) ? $value() : $value;
                }
                continue;
            }

            if ($condition === $this->source) {
                return ($value instanceof Closure) ? $value() : $value;
            }
        }

        if ($this->default === null) {
            $type = gettype($this->source);
            throw new UnhandledSmatchException("Unhandled smatch value of type $type");
        }

        if ($this->default instanceof Closure) {
            return ($this->default)();
        }

        return $this->default;
    }

    /**
     * @param Closure $callback
     * @return mixed
     */
    public function getOr(Closure $callback)
    {
        try {
            return $this->get();
        } catch (UnhandledSmatchException $e) {
            return $callback();
        }
    }
}
