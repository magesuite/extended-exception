<?php

namespace MageSuite\ExtendedException\Processor;

class WebProcessor extends \Monolog\Processor\WebProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_WEB_PROCESSOR = 'extended_exception/processors/web_processor';

    public function __invoke(array $record)
    {
        if(!$this->getConfig(self::XML_PATH_ADD_WEB_PROCESSOR)) {
            return $record;
        }

        return parent::__invoke($record);
    }
}