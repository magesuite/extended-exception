<?php

namespace MageSuite\ExtendedException\Processor;

class ExtraDataProcessor extends \Creitive\Monolog\Processor\ExtraDataProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_EXTRA_DATA_PROCESSOR = 'extended_exception/processors/extra_data_processor';

    public function __invoke(array $record)
    {
        if(!$this->getConfig(self::XML_PATH_ADD_EXTRA_DATA_PROCESSOR)) {
            return $record;
        }

        $this->setExtraData([
            'app_mode' => $this->getAppMode(),
            'session' => isset($_SESSION) ? $_SESSION : [],
            'get' => isset($_GET) ? $_GET : [],
            'post' => isset($_POST) ? $this->hideSensitiveInformations($_POST) : [],
            'cookies' => isset($_COOKIE) ? $_COOKIE : [],
            'server' => isset($_SERVER) ? $_SERVER: [],
        ]);

        return parent::__invoke($record);
    }

    protected function hideSensitiveInformations($array) {
        foreach($array as $key => $value) {
            if(is_array($array[$key])) {
                continue;
            }

            if($this->containsAtLeastOneOf($key, ['password','pass'])) {
                $array[$key] = str_repeat('*', strlen($value));
            }
        }

        return $array;
    }

    protected function containsAtLeastOneOf($string, array $subStrings) {
        foreach($subStrings as $subString) {
            if(strpos($string, $subString) !== false) {
                return true;
            }
        }

        return false;
    }
}
