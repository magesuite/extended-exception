<?php

namespace MageSuite\ExtendedException\Processor;


/**
 * Injects arbitrary extra data in all records.
 *
 * @author Miloš Levačić <milos@levacic.net>
 * @author Krzysztof Kurzydło <krzysztof.kurzydlo@creativestyle.pl>
 */
class ExtraDataProcessor
{
    use \MageSuite\ExtendedException\Service\ScopeConfigProvider;

    const XML_PATH_ADD_EXTRA_DATA_PROCESSOR = 'extended_exception/processors/extra_data_processor';

    /**
     * The currently configured extra data.
     *
     * @var array
     */
    protected $extraData = [];

    /**
     * Create a new processor instance.
     *
     * @param array $extraData Extra data to be added
     */
    public function __construct(array $extraData = [])
    {
        $this->setExtraData($extraData);
    }

    /**
     * Magic method for instance invokation as a function.
     *
     * Merges the passed record's `extra` entry with the configured extra data
     * (overwriting existing keys), and returns the record.
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if(!$this->isExtraDataProcessorEnabled() || $this->isExcluded($record['message'])) {
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

        $record['extra'] = $this->appendExtraFields($record['extra']);

        return $record;
    }

    /**
     * Sets all extra data.
     *
     * @param array $extraData
     */
    public function setExtraData(array $extraData = [])
    {
        $this->extraData = $extraData;
    }

    /**
     * Returns the currently configured extra data.
     *
     * @return array
     */
    public function getExtraData()
    {
        return $this->extraData;
    }

    /**
     * Adds more extra data into the processor.
     *
     * Overwrites existing data without warning.
     *
     * @param array $extraData
     * @return void
     */
    public function addExtraData(array $extraData = [])
    {
        foreach ($extraData as $key => $data) {
            $this->extraData[$key] = $data;
        }
    }

    /**
     * Adds the extra data into the passed array.
     *
     * @param array $extra
     * @return array
     */
    private function appendExtraFields(array $extra)
    {
        foreach ($this->extraData as $key => $value) {
            $extra[$key] = $value;
        }

        return $extra;
    }

    /**
     * Removes the passed extra data keys.
     *
     * @param array $extraDataKeys
     * @return void
     */
    public function removeExtraData(array $extraDataKeys = [])
    {
        foreach ($extraDataKeys as $key) {
            if (array_key_exists($key, $this->extraData)) {
                unset($this->extraData[$key]);
            }
        }
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

    protected function isExtraDataProcessorEnabled(){
        return $this->getConfig(self::XML_PATH_ADD_EXTRA_DATA_PROCESSOR);
    }

    protected function isExcluded($message) {
        return $this->isCronLog($message) ||
            $this->isCacheCleanupLog($message);
    }

    protected function isCronLog($message) {
        return stripos($message, 'Cron Job') !== FALSE;
    }

    protected function isCacheCleanupLog($message)
    {
        return stripos($message, 'cache_clear') !== FALSE;
    }
}
