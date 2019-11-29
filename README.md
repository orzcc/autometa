# AutoMeta - A Meta Tool for Laravel

AutoMeta is a package for **Laravel 5** that provides helpers for some common SEO techniques.

Fix some bugs and make code simple of artesaos/seotools.

## Installation
### 1 - Dependency
The first step is using composer to install the package and automatically update your `composer.json` file, you can do this by running:
```shell
composer require orzcc/autometa
```

### 2 - Provider
You need to update your application configuration in order to register the package so it can be loaded by Laravel, just update your `config/app.php` file adding the following code at the end of your `'providers'` section:

> `config/app.php`
```php
// file START ommited
    'providers' => [
        // other providers ommited
        'Orzcc\AutoMeta\Providers\AutoMetaServiceProvider',
    ],
// file END ommited
```

### 3 - Facade
In order to use the `Meta` facade, you need to register it on the `config/app.php` file, you can do that the following way:

```php
// file START ommited
    'aliases' => [
        // other Facades ommited
        'Meta'   => 'Orzcc\AutoMeta\Facades\AutoMeta',
    ],
// file END ommited
```
## 4 - Usage
### Meta tags Generator
With **Meta** you can create meta tags to the `head`

#### In your controller
```php
use Meta;

class CommomController extends Controller
{

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Meta::setTitle('Home');
        Meta::setDescription('This is my page description');
        
        $posts = Post::all();

        return view('myindex', compact('posts'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $post = Post::find($id);

        Meta::setTitle($post->title);
        Meta::setDescription($post->resume);
        Meta::addMeta('article:published_time', $post->published_date->toW3CString(), 'property');
        Meta::addMeta('article:section', $post->category, 'property');
        Meta::addKeyword(['key1', 'key2', 'key3']);

        return view('myshow', compact('post'));
    }
}
```

### In Your View

```html
<html>
<head>
	{!! Meta::generate() !!}
</head>
<body>

</body>
</html>
```

```html
<html>
<head>
	<title>Title - SubTitle</title>
	<meta name='description' itemprop='description' content='description...' />
	<meta name='keywords' content='key1, key2, key3' />
	<meta property='article:published_time' content='2015-01-31T20:30:11-02:00' />
	<meta property='article:section' content='news' />

</head>
<body>

</body>
</html>
```

#### Configuration
In `autometa.php` configuration file you can determine the properties of the default values and some behaviors.
- **defaults** - What values are displayed if not specified any value for the page display. If the value is `false`, nothing is displayed.
- **webmaster** - Are the settings of tags values for major webmaster tools. If you are `null` nothing is displayed.

#### API (Meta)
```php
Meta::setTitleSeperator($seperator);
Meta::generate::setTitle($title);
Meta::setDescription($description);
Meta::setKeywords($keywords);
Meta::addKeyword($keyword);
Meta::addMeta($meta, $value = null, $name = 'name');

// You can concatenate methods
Meta::setTitle($title)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->addKeyword($keyword)
            ->addMeta($meta, $value);

// Retrieving data
Meta::getTitle();
Meta::getTitleSession();
Meta::getTitleSeperator();
Meta::getKeywords();
Meta::getDescription();
Meta::reset();

Meta::generate();
```

## Sponsor

[Tech Life Land](https://www.techlifeland.com)
