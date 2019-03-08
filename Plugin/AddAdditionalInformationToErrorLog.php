<?php

namespace MageSuite\ExtendedException\Plugin;

class AddAdditionalInformationToErrorLog
{
    /**
     * @var callable[]
     */
    protected $processors;

    public function __construct(\Magento\Framework\Logger\Monolog $monolog)
    {
        $this->processors = $monolog->getProcessors();
    }

    /**
     * This is the only place where we can plug in when top level exception is thrown and error log
     * is written to var/report folder
     * @param \Magento\Framework\Serialize\Serializer\Json $subject
     * @param $data
     * @return array
     */
    public function beforeSerialize(\Magento\Framework\Serialize\Serializer\Json $subject, $data) {
        if(!$this->isErrorReport($data)) {
            return [$data];
        }

        $data['extra'] = [];

        foreach($this->processors as $processor) {
            $data = $processor($data);
        }

        return [$data];
    }

    /**
     * @param $data
     * @return bool
     */
    protected function isErrorReport($data)
    {
        if($data instanceof \stdClass){
            return false;
        }

        return isset($data[0]) and isset($data[1]) and isset($data['url']) and isset($data['script_name']);
    }
}