<?php
/**
 * @file display-markdown.php
 */
/**
 * $_html 변수에 값을 넣어 이 스크립트를 호출하면 된다.
 * 사용 예)
 * $_html = "<h1> .. </h1>";
 * include THEME_DIR . '/etc/markdown/display-markdown.php';
 */
global $_html;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\TableOfContents\TableOfContentsExtension;

// 테이블을 사용하기 위해서는 이 것을 추가
use League\CommonMark\Extension\Table\TableExtension;

// Obtain a pre-configured Environment with all the CommonMark parsers/renderers ready-to-go
$environment = Environment::createCommonMarkEnvironment();

// Add the two extensions
$environment->addExtension(new HeadingPermalinkExtension());
$environment->addExtension(new TableOfContentsExtension());



// 테이블을 사용하기 위해서는 이 것을 추가
$environment->addExtension(new TableExtension());

// Set your configuration
$config = [
    // Extension defaults are shown below
    // If you're happy with the defaults, feel free to remove them from this array
    'table_of_contents' => [
        'html_class' => 'table-of-contents',
        'position' => 'placeholder',
        'style' => 'bullet',
        'min_heading_level' => 1,
        'max_heading_level' => 6,
        'normalize' => 'relative',
        'placeholder' => '[TOC]',
    ],
];

// Instantiate the converter engine and start converting some Markdown!
$converter = new CommonMarkConverter($config, $environment);
echo $converter->convertToHtml($_html);

?>