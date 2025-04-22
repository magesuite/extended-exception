<?php

namespace MageSuite\ExtendedException\Processor;

class WebProcessor extends \Monolog\Processor\WebProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_WEB_PROCESSOR = 'extended_exception/processors/web_processor';

    public function __invoke(\Monolog\LogRecord $record): \Monolog\LogRecord
    {
        if (!isset($this->serverData['REQUEST_URI']) || !$this->getConfig(self::XML_PATH_ADD_WEB_PROCESSOR)) {
            return $record;
        }

        return parent::__invoke($record);
    }
}
