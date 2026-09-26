<?php


namespace Ipol\OzonPay\SDK\Logger;


class Logger extends AbstractLogger
{

    private $filePath;

    public function __construct(string $logFile='')
    {
        $this->filePath = $logFile;
    }

    /**
     * @param $level
     * @param $message
     * @param array $context
     */
    public function log($level, $message, array $context = [])
    {
        $dataString =
            trim(strtr(self::getMsgTemplate(), [
                '{date}' => $this->getDate(),
                '{level}' => $level,
                '{message}' => $message,
                '{context}' => ($this->contextStringify($context)) ?
                    'context:' . PHP_EOL . $this->contextStringify($context) . PHP_EOL :
                    '',
            ]));

        if (!file_exists(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0777, 1);
        }

        file_put_contents($this->filePath,
            trim($dataString) . PHP_EOL . '--------------------------------------------' . PHP_EOL,
            FILE_APPEND);
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return (new \DateTime())->format('Y-m-d H:i:s.u');
    }

    /**
     * @param array $context
     * @return false|string|null
     */
    public function contextStringify(array $context = [])
    {
        return !empty($context) ? json_encode($context) : null;
    }

    /**
     * @return string
     */
    public static function getMsgTemplate(): string
    {
        return '{date}' . PHP_EOL .
            '{level}' . PHP_EOL .
            '{message}' . PHP_EOL .
            '{context}';
    }

    /**
     * @param $message
     * @param array $context
     * @return string
     */
    protected function interpolate($message, array $context = array()): string
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (is_array($val)) {
                $val = print_r($val, true);
            }
            if (!is_object($val) || method_exists($val, '__toString')) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }

}