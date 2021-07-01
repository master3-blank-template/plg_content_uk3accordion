# UIkit 3 Accordion - Content plugin

![Version](https://img.shields.io/badge/VERSION-1.1.0-0366d6.svg?style=for-the-badge)
![Joomla](https://img.shields.io/badge/joomla-3.7+-1A3867.svg?style=for-the-badge)
![Php](https://img.shields.io/badge/php-5.6+-8892BF.svg?style=for-the-badge)

_description in Russian [here](README.ru.md)_

Accordion content based on the UIkit 3 framework. Content plugin for Joomla! 3

The plugin contains all the basic settings, implemented in the framework UIkit 3, necessary to display a full-fledged accordion.

**Attention!** The plugin does not contain scripts and framework styles, connect them yourself in your template (it is assumed that your template is already based on this framework).

## Usage

Indert into content editor:

```text
{accordion Title 1}

Your content is inside the "Title 1" slide

{accordion Title 2}

Your content is inside the "Title 2" slide

{/accordion}
```

HTML tags immediately adjacent to the shortcodes in curly braces will be cut.

Multiple slide blocks per page are supported. Nested slides are not supported.
