<?php namespace Nano7\View\Frames;

use Nano7\View\View;
use Nano7\Foundation\Events\Dispatcher;

class Frames extends Dispatcher
{
    /**
     * @param $frame
     * @param array $payload
     * @param bool $echo
     * @param null $separator
     * @return bool|string
     */
    public function render($frame, $payload = [], $echo = false, $separator = null)
    {
        $htmls = [];

        $returns = $this->fire($frame, $payload);

        foreach ($returns as $ret) {
            if (! is_null($ret)) {

                // Verificar se eh uma visao
                if ($ret instanceof View) {
                    $ret = $ret->render();
                }

                $htmls[] = $ret;
            }
        }

        $separator = trim($separator);
        $html = implode($separator, $htmls);

        if ($echo) {
            echo $html;
            return true;
        }

        return $html;
    }
}