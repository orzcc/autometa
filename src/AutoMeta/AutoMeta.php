<?php namespace Orzcc\AutoMeta;

use Orzcc\AutoMeta\Contracts\MetaTags as MetaTagsContract;
use Illuminate\Config\Repository as Config;

class AutoMeta implements MetaTagsContract
{
    /**
     * The meta title.
     *
     * @var string
     */
    protected $title;

    /**
     * The meta title session.
     *
     * @var string
     */
    protected $title_session;

    /**
     * The title tag seperator.
     *
     * @var array
     */
    protected $title_seperator;

    /**
     * The meta description.
     *
     * @var string
     */
    protected $description;

    /**
     * The meta keywords.
     *
     * @var array
     */
    protected $keywords = [];

    /**
     * extra metatags
     *
     * @var array
     */
    protected $metatags = [];

    /**
     * @var Config
     */
    protected $config;

    /**
     * The webmaster tags.
     *
     * @var array
     */
    protected $webmasterTags = [
        'google'   => "google-site-verification",
        'bing'     => "msvalidate.01",
        'alexa'    => "alexaVerifyID",
        'pintrest' => "p:domain_verify",
        'yandex'   => "yandex-verification"
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config = array())
    {
        $this->config = new Config($config);
    }

    /**
     * Generates meta tags
     *
     * @return string
     */
    public function generate()
    {
        $this->loadWebMasterTags();

        $title       = $this->getTitle();
        $description = $this->getDescription();
        $keywords    = $this->getKeywords();
        $metatags    = $this->metatags;

        $html   = [];

        if($title):
            $html[] = "<title>$title</title>";
        endif;

        if($title):
            $html[] = "<meta name=\"description\" itemprop=\"description\" content=\"{$description}\" />";
        endif;

        if (!empty($keywords)):
            $keywords = implode(',', $keywords);
            $html[]   = "<meta name=\"keywords\" content=\"{$keywords}\" />";
        endif;

        foreach ($metatags as $key => $value):
            $name    = $value[0];
            $content = $value[1];
            $html[]  = "<meta {$name}=\"{$key}\" content=\"{$content}\" />";
        endforeach;

        return implode(PHP_EOL, $html);
    }

    /**
     * Sets the title
     *
     * @param string $title
     *
     * @return MetaTagsContract
     */
    public function setTitle($title)
    {
        // clean title
        $title = strip_tags($title);

        // store title session
        $this->title_session = $title;

        // store title
        $this->title = $this->parseTitle($title);

        return $this;
    }

    /**
     * Sets the seperator for the title tag.
     *
     * @param string $seperator
     *
     * @return MetaTagsContract
     */
    public function setTitleSeperator($seperator)
    {
        // sets the title seperator ie - title <seperator> <site>
        $this->title_seperator = $seperator;

        return $this;
    }

    /**
     * @param string $description
     *
     * @return MetaTagsContract
     */
    public function setDescription($description)
    {
        // clean and store description
        $this->description = strip_tags($description);

        return $this;
    }

    /**
     * Sets the list of keywords, you can send an array or string separated with commas
     * also clears the previously set keywords
     *
     * @param string|array $keywords
     *
     * @return MetaTagsContract
     */
    public function setKeywords($keywords)
    {

        if (!is_array($keywords)):
            $keywords = explode(', ', $this->keywords);
        endif;

        // clean keywords
        $keywords = array_map('strip_tags', $keywords);

        // store keywords
        $this->keywords = $keywords;

        return $this;
    }

    /**
     * Add a keyword
     *
     * @param string|array $keyword
     *
     * @return MetaTagsContract
     */
    public function addKeyword($keyword)
    {
        if (is_array($keyword)):
            $this->keywords = array_merge($keyword, $this->keywords);
        else:
            $this->keywords[] = strip_tags($keyword);
        endif;

        return $this;
    }

    /**
     * Add a custom meta tag.
     *
     * @param string|array $meta
     * @param string       $value
     * @param string       $name
     *
     * @return MetaTagsContract
     */
    public function addMeta($meta, $value = null, $name = 'name')
    {
        if (is_array($meta)):
            foreach ($meta as $key => $value):
                $this->metatags[$key] = array($name, $value);
            endforeach;
        else:
            $this->metatags[$meta] = array($name, $value);
        endif;
    }

    /**
     * Takes the title formatted for display
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title ?: $this->config->get('defaults.title', null);
    }

    /**
     * takes the title that was set
     *
     * @return string
     */
    public function getTitleSession()
    {
        return $this->title_session ?: $this->getTitle();
    }

    /**
     * takes the title that was set
     *
     * @return string
     */
    public function getTitleSeperator()
    {
        return $this->title_seperator ?: $this->config->get('defaults.separator', ' - ');
    }

    /**
     * Get the Meta keywords.
     *
     * @return array
     */
    public function getKeywords()
    {
        return $this->keywords ?: $this->config->get('defaults.keywords', []);
    }

    /**
     * Get the Meta description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description ?: $this->config->get('defaults.description', null);
    }

    /**
     * Reset all data.
     *
     * @return void
     */
    public function reset()
    {
        $this->description   = null;
        $this->title_session = null;
        $this->metatags      = [];
        $this->keywords      = [];
    }

    /**
     * Get parsed title.
     *
     * @param string $title
     *
     * @return string
     */
    protected function parseTitle($title)
    {
        return $this->config->get('defaults.title') ? $title . $this->getTitleSeperator() . $this->config->get('defaults.title', null) : $title;
    }

    protected function loadWebMasterTags()
    {
        foreach ($this->config->get('webmaster_tags', []) as $name => $value):
            if (!empty($value)):
                $meta = array_get($this->webmasterTags, $name, $name);
                $this->addMeta($meta, $value);
            endif;
        endforeach;
    }

}
