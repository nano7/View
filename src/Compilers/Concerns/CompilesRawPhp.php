<?php namespace Nano7\View\Compilers\Concerns;

trait CompilesRawPhp
{
    /**
     * Compile the raw PHP statements into valid PHP.
     *
     * @param  string  $expression
     * @return string
     */
    protected function compilePhp($expression)
    {
        if ($expression) {
            return "<?php {$expression}; ?>";
        }

        return '@php';
    }
}
