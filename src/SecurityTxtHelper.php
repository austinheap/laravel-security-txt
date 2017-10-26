<?php
/**
 * src/SecurityTxtHelper.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.0
 */

declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxtHelper
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxtHelper.html
 */
class SecurityTxtHelper
{

    /**
     * Internal version number.
     *
     * @var string
     */
    const VERSION               = '0.3.0';

    /**
     * Internal SecurityTxt object.
     *
     * @var \AustinHeap\Security\Txt\Writer
     */
    protected $writer           = null;

    /**
     * Internal array of log entries.
     *
     * @var array
     */
    protected $logEntries       = [];

    /**
     * Enable built-in cache.
     *
     * @var bool
     */
    protected $cache            = false;

    /**
     * Minutes to cache output.
     *
     * @var int
     */
    protected $cacheTime        = null;

    /**
     * Cache key to use.
     *
     * @var string
     */
    protected $cacheKey         = 'cache:AustinHeap\Security\Txt\SecurityTxt';

    /**
     * Create a new SecurityTxtHelper instance.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function __construct()
    {
        $this->writer = new Writer();

        $keys = [
            'security-txt.enabled'          => ['validator' => 'is_bool',       'setter' => 'setEnabled',           'self' => true],
            'security-txt.debug'            => ['validator' => 'is_bool',       'setter' => 'setDebug'],
            'security-txt.cache'            => ['validator' => 'is_bool',       'setter' => 'setCache',             'self' => true],
            'security-txt.cache-time'       => ['validator' => 'is_numeric',    'setter' => 'setCacheTime',         'self' => true],
            'security-txt.cache-key'        => ['validator' => 'is_string',     'setter' => 'setCacheKey',          'self' => true],
            'security-txt.comments'         => ['validator' => 'is_bool',       'setter' => 'setComments'],
            'security-txt.contacts'         => ['validator' => 'is_array',      'setter' => 'setContacts'],
            'security-txt.encryption'       => ['validator' => 'is_string',     'setter' => 'setEncryption'],
            'security-txt.disclosure'       => ['validator' => 'is_string',     'setter' => 'setDisclosure'],
            'security-txt.acknowledgement'  => ['validator' => 'is_string',     'setter' => 'setAcknowledgement'],
        ];

        foreach ($keys as $key => $mapping)
        {
            if (empty(config($key, null)))
            {
                $this->addLogEntry('"' . __CLASS__ . '" cannot process empty value for key "' . $key . '".', 'notice');
                continue;
            }

            if (!$mapping['validator'](config($key)))
            {
                $this->addLogEntry('"' . __CLASS__ . '" cannot find mapping "validator" method named "' . $mapping['setter'] . '".', 'warning');
                continue;
            }

            if (array_key_exists('self', $mapping) &&
                is_bool($mapping['self']) &&
                $mapping['self'] === true)
            {
                if (!method_exists($this, $mapping['setter']))
                {
                    $this->addLogEntry('"' . __CLASS__ . '" cannot find mapping "setter" method on object "' . get_class($this) . '" named "' . $mapping['setter'] . '".', 'error');
                    continue;
                }

                $this->{$mapping['setter']}(config($key));
            }
            else
            {
                if (!method_exists($this->writer, $mapping['setter']))
                {
                    $this->addLogEntry('"' . __CLASS__ . '" cannot find mapping "setter" method on object "' . get_class($this->writer) . '" named "' . $mapping['setter'] . '".', 'error');
                    continue;
                }

                $this->writer->{$mapping['setter']}(config($key));
            }
        }

        return $this;
    }

    /**
     * Add log entry.
     *
     * @param  string       $text
     * @param  string       $level
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function addLogEntry(string $text, string $level = 'info'): SecurityTxtHelper
    {
        \Log::$level($text);

        $this->logEntries[] = ['text' => $text, 'level' => $level];

        return $this;
    }

    /**
     * Fetches the raw text of the document.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function fetch(): string
    {
        if ($this->cache) {
            $text = cache($this->cacheKey, null);

            if (!is_null($text))
                return $text;
        }

        $text = $this->writer
                     ->generate()
                     ->getText();

        if ($this->writer->getComments())
            $text .= '# Cache is ' . ($this->cache ? 'enabled with key "' . $this->cacheKey . '"' : 'disabled') . '.' . PHP_EOL .
                     '#' . PHP_EOL;

        if ($this->cache)
            cache([$this->cacheKey => $text], now()->addMinutes($this->cacheTime));

        return empty($text) ? '' : $text;
    }

    /**
     * Enable the enabled flag.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function enable(): SecurityTxtHelper
    {
        return $this->setEnabled(true);
    }

    /**
     * Disable the enabled flag.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function disable(): SecurityTxtHelper
    {
        return $this->setEnabled(false);
    }

    /**
     * Set the enabled flag.
     *
     * @param  bool         $enabled
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function setEnabled(bool $enabled): SecurityTxtHelper
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get the enabled flag.
     *
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Enable the cache flag.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function enableCache(): SecurityTxtHelper
    {
        return $this->setCache(true);
    }

    /**
     * Disable the cache flag.
     *
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function disableCache(): SecurityTxtHelper
    {
        return $this->setCache(false);
    }

    /**
     * Set the cache flag.
     *
     * @param  bool         $cache
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function setCache(bool $cache): SecurityTxtHelper
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get the cache flag.
     *
     * @return bool
     */
    public function getCache(): bool
    {
        return $this->cache;
    }

    /**
     * Set the cache key.
     *
     * @param  string       $cacheKey
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function setCacheKey(string $cacheKey): SecurityTxtHelper
    {
        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * Get the cache key.
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    /**
     * Set the cache time.
     *
     * @param  int          $cacheTime
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    public function setCacheTime(int $cacheTime): SecurityTxtHelper
    {
        $this->cacheTime = $cacheTime;

        return $this;
    }

    /**
     * Get the cache time.
     *
     * @return int
     */
    public function getCacheTime(): int
    {
        return $this->cacheTime;
    }

}
