<?php
/**
 * src/SecurityTxt.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxt
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxt.html
 */
class SecurityTxt
{

    /**
     * Internal lines cache.
     *
     * @var array
     */
    protected $lines            = [];

    /**
     * Internal text cache.
     *
     * @var string
     */
    protected $text             = null;

    /**
     * Enable SecurityTxt.
     *
     * @var bool
     */
    protected $enabled          = false;

    /**
     * Enable debug output.
     *
     * @var bool
     */
    protected $debug            = false;

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
     * Enable built-in comments.
     *
     * @var bool
     */
    protected $comments         = true;

    /**
     * The security contacts to list.
     *
     * @var array
     */
    protected $contacts         = [];

    /**
     * The PGP key file URL.
     *
     * @var string
     */
    protected $encryption       = null;

    /**
     * The disclosure policy.
     *
     * @var string
     */
    protected $disclosure       = null;

    /**
     * The acknowledgement URL.
     *
     * @var string
     */
    protected $acknowledgement  = null;

    /**
     * Create a new SecurityTxt instance.
     *
     * @return SecurityTxt
     */
    public function __construct()
    {
        $keys = [
            'security-txt.enabled'          => ['validator' => 'is_bool',       'setter' => 'setEnabled'],
            'security-txt.debug'            => ['validator' => 'is_bool',       'setter' => 'setDebug'],
            'security-txt.cache'            => ['validator' => 'is_bool',       'setter' => 'setCache'],
            'security-txt.cache-time'       => ['validator' => 'is_numeric',    'setter' => 'setCacheTime'],
            'security-txt.cache-key'        => ['validator' => 'is_string',     'setter' => 'setCacheKey'],
            'security-txt.comments'         => ['validator' => 'is_bool',       'setter' => 'setComments'],
            'security-txt.contacts'         => ['validator' => 'is_array',      'setter' => 'setContacts'],
            'security-txt.encryption'       => ['validator' => 'is_string',     'setter' => 'setEncryption'],
            'security-txt.disclosure'       => ['validator' => 'is_string',     'setter' => 'setDisclosure'],
            'security-txt.acknowledgement'  => ['validator' => 'is_string',     'setter' => 'setAcknowledgement'],
        ];

        foreach ($keys as $key => $mapping)
            if (config($key, null) !== null &&
                $mapping['validator'](config($key)))
                $this->{$mapping['setter']}(config($key));

        return $this;
    }

    /**
     * Generate the data.
     *
     * @param  bool         $reset
     * @return SecurityTxt
     */
    public function generate(bool $reset = false): SecurityTxt
    {
        if ($this->cache) {
            $output = cache($this->cacheKey, null);

            if (!is_null($output))
                return $this->setText($output);
        }

        if ($this->debug)
            $time = microtime(true);

        if ($reset)
            $this->resetLines();

        if ($this->comments)
            $this->addComment('Our security address');

        if (empty($this->contacts))
            throw new \Exception('One (or more) contacts must be defined.');

        foreach ($this->contacts as $contact)
            $this->addLine('Contact: ' . trim($contact));

        if (!empty($this->encryption)) {
            if ($this->comments)
                $this->addSpacer()
                     ->addComment('Our PGP key');

            $this->addLine('Encryption: ' . trim($this->encryption));
        }

        if (!empty($this->disclosure)) {
            if ($this->comments)
                $this->addSpacer()
                     ->addComment('Our disclosure policy');

            $this->addLine('Disclosure: ' . trim(ucfirst($this->disclosure)));
        }

        if (!empty($this->acknowledgement)) {
            if ($this->comments)
                $this->addSpacer()
                     ->addComment('Our public acknowledgement');

            $this->addLine('Acknowledgement: ' . trim($this->acknowledgement));
        }

        if ($this->debug)
            $this->addSpacer()
                 ->addComment()
                 ->addComment('Generated by https://github.com/austinheap/laravel-security-txt')
                 ->addComment('in ' . round((microtime(true) - $time) * 1000, 6) . ' seconds on ' . now() . '.')
                 ->addComment()
                 ->addComment('Cache is ' . ($this->cache ? 'enabled with key "' . $this->cacheKey . '"' : 'disabled') . '.')
                 ->addComment()
                 ->addSpacer();

        $output = implode(PHP_EOL, $this->lines);

        if ($this->cache)
            cache([$this->cacheKey => $output], now()->addMinutes($this->cacheTime));

        return $this->setText($output);
    }

    /**
     * Enable the enabled flag.
     *
     * @return SecurityTxt
     */
    public function enable(): SecurityTxt
    {
        return $this->setEnabled(true);
    }

    /**
     * Disable the enabled flag.
     *
     * @return SecurityTxt
     */
    public function disable(): SecurityTxt
    {
        return $this->setEnabled(false);
    }

    /**
     * Set the enabled flag.
     *
     * @param  bool         $enabled
     * @return SecurityTxt
     */
    public function setEnabled(bool $enabled): SecurityTxt
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
     * Enable the comments flag.
     *
     * @return SecurityTxt
     */
    public function enableComments(): SecurityTxt
    {
        return $this->setComments(true);
    }

    /**
     * Disable the comments flag.
     *
     * @return SecurityTxt
     */
    public function disableComments(): SecurityTxt
    {
        return $this->setComments(false);
    }

    /**
     * Set the comments flag.
     *
     * @param  string       $comments
     * @return SecurityTxt
     */
    public function setComments(bool $comments): SecurityTxt
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get the comments flag.
     *
     * @return bool
     */
    public function getComments(): bool
    {
        return $this->comments;
    }

    /**
     * Enable the debug flag.
     *
     * @return SecurityTxt
     */
    public function enableDebug(): SecurityTxt
    {
        return $this->setDebug(true);
    }

    /**
     * Disable the debug flag.
     *
     * @return SecurityTxt
     */
    public function disableDebug(): SecurityTxt
    {
        return $this->setDebug(false);
    }

    /**
     * Set the debug flag.
     *
     * @param  bool         $debug
     * @return SecurityTxt
     */
    public function setDebug(bool $debug): SecurityTxt
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * Get the debug flag.
     *
     * @return bool
     */
    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Enable the cache flag.
     *
     * @return SecurityTxt
     */
    public function enableCache(): SecurityTxt
    {
        return $this->setCache(true);
    }

    /**
     * Disable the cache flag.
     *
     * @return SecurityTxt
     */
    public function disableCache(): SecurityTxt
    {
        return $this->setCache(false);
    }

    /**
     * Set the cache flag.
     *
     * @param  bool         $cache
     * @return SecurityTxt
     */
    public function setCache(bool $cache): SecurityTxt
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
     * @return SecurityTxt
     */
    public function setCacheKey(string $cacheKey): SecurityTxt
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
     * @return SecurityTxt
     */
    public function setCacheTime(int $cacheTime): SecurityTxt
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
     * Set the text.
     *
     * @param  string       $text
     * @return SecurityTxt
     */
    public function setText(string $text): SecurityTxt
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get the text.
     *
     * @param  bool   $generate
     * @param  bool   $reset
     * @return string
     */
    public function getText(bool $generate = false, bool $reset = false): string
    {
        if ($generate)
            $this->generate($reset);

        return $this->text === null ? '' : $this->text;
    }

    /**
     * Set the contacts.
     *
     * @param  array        $contacts
     * @return SecurityTxt
     */
    public function setContacts(array $contacts): SecurityTxt
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get the contacts.
     *
     * @return array
     */
    public function getContacts(): array
    {
        return is_null($this->contacts) ? [] : $this->contacts;
    }

    /**
     * Add a contact.
     *
     * @param  string $contact
     * @return SecurityTxt
     */
    public function addContact(string $contact): SecurityTxt
    {
        return $this->addContacts([$contact]);
    }

    /**
     * Add contacts.
     *
     * @param  array $contacts
     * @return SecurityTxt
     */
    public function addContacts(array $contacts): SecurityTxt
    {
        foreach ($contacts as $contact)
            $this->contact[$contact] = true;

        return $this;
    }

    /**
     * Remove a contact.
     *
     * @param  string $contact
     * @return SecurityTxt
     */
    public function removeContact(string $contact): SecurityTxt
    {
        $this->removeContacts([$contact]);

        return $this;
    }

    /**
     * Remove contacts.
     *
     * @param  array $contacts
     * @return SecurityTxt
     */
    public function removeContacts(array $contacts): SecurityTxt
    {
        foreach ($contacts as $contact)
            if (array_key_exists($contact, $this->contacts))
                unset($this->contacts[$contact]);

        return $this;
    }

    /**
     * Set the encryption.
     *
     * @param  string $encryption
     * @return SecurityTxt
     */
    public function setEncryption(string $encryption): SecurityTxt
    {
        if (filter_var($encryption, FILTER_VALIDATE_URL) === false)
            throw new \Exception('Encryption must be a well-formed URL.');

        $this->encryption = $encryption;

        return $this;
    }

    /**
     * Get the encryption.
     *
     * @return string
     */
    public function getEncryption(): string
    {
        return $this->encryption;
    }

    /**
     * Set the disclosure policy.
     *
     * @param  string $disclosure
     * @return SecurityTxt
     */
    public function setDisclosure(string $disclosure): SecurityTxt
    {
        if (!in_array(trim(strtolower($disclosure)), ['full', 'partial', 'none']))
            throw new \Exception('Disclosure policy must be either "full", "partial", or "none".');

        $this->disclosure = $disclosure;

        return $this;
    }

    /**
     * Get the disclosure policy.
     *
     * @return string
     */
    public function getDisclosure(): string
    {
        return $this->disclosure;
    }

    /**
     * Set the acknowledgement URL.
     *
     * @param  string $acknowledgement
     * @return SecurityTxt
     */
    public function setAcknowledgement(string $acknowledgement): SecurityTxt
    {
        if (filter_var($acknowledgement, FILTER_VALIDATE_URL) === false)
            throw new \Exception('Acknowledgement must be a well-formed URL.');

        $this->acknowledgement = $acknowledgement;

        return $this;
    }

    /**
     * Get the acknowledgement URL.
     *
     * @return string
     */
    public function getAcknowledgement(): string
    {
        return $this->acknowledgement;
    }

    /**
     * Add a comment.
     *
     * @param  string $comment
     * @return SecurityTxt
     */
    private function addComment(string $comment = ''): SecurityTxt
    {
        $comment = trim($comment);

        if (!empty($comment))
            $comment = ' ' . $comment;

        return $this->addLine('#' . $comment);
    }

    /**
     * Add a spacer.
     *
     * @return SecurityTxt
     */
    private function addSpacer(): SecurityTxt
    {
        return $this->addLine('');
    }

    /**
     * Add a line.
     *
     * @param  string $line
     * @return SecurityTxt
     */
    private function addLine(string $line): SecurityTxt
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Reset the lines.
     *
     * @return SecurityTxt
     */
    private function resetLines(): SecurityTxt
    {
        $this->lines = [];

        return $this;
    }

}
