<?php
/**
 * src/SecurityTxtHelper.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */
declare(strict_types=1);

namespace AustinHeap\Security\Txt;

use Log;
use Exception;

/**
 * SecurityTxtHelper.
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
    const VERSION = '0.4.0';

    /**
     * Enable the package.
     *
     * @var bool
     */
    protected $enabled = null;

    /**
     * Internal SecurityTxt object.
     *
     * @var \AustinHeap\Security\Txt\Writer
     */
    protected $writer = null;

    /**
     * Internal array of log entries.
     *
     * @var array
     */
    protected $logEntries = [];

    /**
     * Enable built-in cache.
     *
     * @var bool
     */
    protected $cache = false;

    /**
     * Minutes to cache output.
     *
     * @var int
     */
    protected $cacheTime = null;

    /**
     * Cache key to use.
     *
     * @var string
     */
    protected $cacheKey = 'cache:AustinHeap\Security\Txt\SecurityTxt';

    /**
     * Create a new SecurityTxtHelper instance.
     *
     * @return SecurityTxtHelper
     */
    public function __construct()
    {
        $this->buildWriter();

        return $this;
    }

    /**
     * Returns the default config key => php-security-txt mappings.
     *
     * @return array
     */
    public static function buildWriterDefaultKeys()
    {
        return [
            'security-txt.enabled'         => ['validator' => 'is_bool', 'setter' => 'setEnabled', 'self' => true],
            'security-txt.debug'           => ['validator' => 'is_bool', 'setter' => 'setDebug'],
            'security-txt.cache'           => ['validator' => 'is_bool', 'setter' => 'setCache', 'self' => true],
            'security-txt.cache-time'      => ['validator' => 'is_numeric', 'setter' => 'setCacheTime', 'self' => true],
            'security-txt.cache-key'       => ['validator' => 'is_string', 'setter' => 'setCacheKey', 'self' => true],
            'security-txt.comments'        => ['validator' => 'is_bool', 'setter' => 'setComments'],
            'security-txt.contacts'        => ['validator' => 'is_array', 'setter' => 'addContacts'],
            'security-txt.encryption'      => ['validator' => 'is_string', 'setter' => 'setEncryption'],
            'security-txt.disclosure'      => ['validator' => 'is_string', 'setter' => 'setDisclosure'],
            'security-txt.acknowledgement' => ['validator' => 'is_string', 'setter' => 'setAcknowledgement'],
        ];
    }

    /**
     * Builds the internal SecurityTxt Writer.
     *
     * @param array $keys
     *
     * @return SecurityTxtHelper
     */
    public function buildWriter(array $keys = null): self
    {
        $this->writer = new Writer();

        $keys = is_array($keys) ? $keys : self::buildWriterDefaultKeys();

        foreach ($keys as $key => $mapping) {
            if (is_null(config($key, null))) {
                $this->addLogEntry('"'.__CLASS__.'" cannot process null value for key "'.$key.'".', 'debug');
                continue;
            } else if (! function_exists($mapping['validator'])) {
                $this->addLogEntry('"'.__CLASS__.'" cannot find "validator" function named "'.$mapping['validator'].'".', 'warning');
                continue;
            } else if (! $mapping['validator'](config($key))) {
                $this->addLogEntry('"'.__CLASS__.'" failed the "validator" function named "'.$mapping['validator'].'".', 'warning');
                continue;
            } else if (array_key_exists('self', $mapping) && is_bool($mapping['self']) && $mapping['self'] === true) {
                if (! method_exists($this, $mapping['setter'])) {
                    $this->addLogEntry('"'.__CLASS__.'" cannot find mapping "setter" method on object "'.get_class($this).'" named "'.$mapping['setter'].'".', 'error');
                    continue;
                }

                $this->{$mapping['setter']}(config($key));
            } else {
                if (! method_exists($this->writer, $mapping['setter'])) {
                    $this->addLogEntry('"'.__CLASS__.'" cannot find mapping "setter" method on object "'.get_class($this->writer).'" named "'.$mapping['setter'].'".', 'error');
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
     * @param  string $text
     * @param  string $level
     *
     * @return SecurityTxtHelper
     */
    public function addLogEntry(string $text, string $level = 'debug'): self
    {
        Log::$level($text);

        $this->logEntries[] = ['text' => $text, 'level' => $level];

        return $this;
    }

    /**
     * Add log entries.
     *
     * @return array
     */
    public function getLogEntries(): array
    {
        return $this->logEntries;
    }

    /**
     * Clears log entries.
     *
     * @return SecurityTxtHelper
     */
    public function clearLogEntries(): self
    {
        $this->logEntries = [];

        return $this;
    }

    /**
     * Fetches the raw text of the document.
     *
     * @return SecurityTxtHelper
     */
    public function fetch(): string
    {
        if ($this->cache) {
            $text = cache($this->cacheKey, null);

            if (! is_null($text)) {
                return $text;
            }
        }

        $text = $this->writer
            ->execute()
            ->getText();

        if ($this->writer->getDebug()) {
            $text .= '# Cache is '.($this->cache ? 'enabled with key "'.$this->cacheKey.'"' : 'disabled').'.'.PHP_EOL.
                     '#'.PHP_EOL;
        }

        if ($this->cache) {
            cache([$this->cacheKey => $text], now()->addMinutes($this->cacheTime));
        }

        return empty($text) ? '' : $text;
    }

    /**
     * Enable the enabled flag.
     *
     * @return SecurityTxtHelper
     */
    public function enable(): self
    {
        return $this->setEnabled(true);
    }

    /**
     * Disable the enabled flag.
     *
     * @return SecurityTxtHelper
     */
    public function disable(): self
    {
        return $this->setEnabled(false);
    }

    /**
     * Set the enabled flag.
     *
     * @param  bool $enabled
     *
     * @return SecurityTxtHelper
     */
    public function setEnabled(bool $enabled): self
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
        return is_null($this->enabled) ? false : $this->enabled;
    }

    /**
     * Enable the cache flag.
     *
     * @return SecurityTxtHelper
     */
    public function enableCache(): self
    {
        return $this->setCache(true);
    }

    /**
     * Disable the cache flag.
     *
     * @return SecurityTxtHelper
     */
    public function disableCache(): self
    {
        return $this->setCache(false);
    }

    /**
     * Set the cache flag.
     *
     * @param  bool $cache
     *
     * @return SecurityTxtHelper
     */
    public function setCache(bool $cache): self
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
     * Clear the cache.
     *
     * @return SecurityTxtHelper
     */
    public function clearCache(): self
    {
        cache()->delete($this->cacheKey);

        return $this;
    }

    /**
     * Set the cache key.
     *
     * @param  string $cacheKey
     *
     * @return SecurityTxtHelper
     */
    public function setCacheKey(string $cacheKey): self
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
     * @param  int $cacheTime
     *
     * @return SecurityTxtHelper
     */
    public function setCacheTime(int $cacheTime): self
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

    /**
     * Determines if a SecurityTxt Writer is set.
     *
     * @return bool
     */
    public function hasWriter(): bool
    {
        return ! is_null($this->writer);
    }

    /**
     * Gets the internal SecurityTxt Writer.
     *
     * @return Writer
     */
    public function getWriter(): Writer
    {
        if (! $this->hasWriter()) {
            throw new Exception('Writer not set.');
        }

        return $this->writer;
    }

    /**
     * Sets the internal SecurityTxt Writer.
     *
     * @param Writer|null $writer
     *
     * @return SecurityTxtHelper
     */
    public function setWriter($writer): self
    {
        $this->writer = $writer;

        return $this;
    }
}
