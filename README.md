# CakePHP InlineStyle helper

A CakePHP 3 helper to automatically transform styles from `<style>` blocks to `style=""` attributes. Useful for HTML emails.

Internally uses the library https://github.com/christiaan/InlineStyle

## Installation

    composer require bigfishtv/cakephp-inline-style

## Usage

For emails:

```php
$email->helpers(['InlineStyle']);
```

For regular pages. In your `App\View\AppView` class:

```php
$this->loadHelper('InlineStyle');
```
