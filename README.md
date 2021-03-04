# Sitepilot

Brings the powers of Sitepilot directly to your WordPress website. It will revolutionize the way you use, manage and develop WordPress sites by adding useful modules and tools. This plugin is optimized for use on the [Sitepilot managed WordPress hosting platform](https://sitepilot.io) but should also work on other platforms.

[🚀 Download the release here.](https://github.com/sitepilot/sitepilot/releases)

![Screenshot](./screenshot.png)

## Features

* Log: tracks changes to your site and sends reports to your email.
* Client Role: add a client role with limited capabilities to your site.
* Custom Code: add custom code to the WordPress head, body and footer.
* Sitepilot Support: add a Sitepilot support widget to the WordPress dashboard for quick support.

## Developer Tools

All developer modules and tools are disabled by default. You can use these modules to speedup WordPress development and give your clients a better WordPress experience. Use WordPress filters to enable a module and change its settings.

### Blocks

This module enables a powerfull library for registering blocks and block fields with PHP and rendering block views using Blade. The plugin will search for blocks in your theme's `blocks` folder and registers them using ACF. You can find some examples [here](blocks).

Add the following filter to your theme to activate this module:

```php
add_filter('sp_blocks_enabled', '__return_true');
```

Each loaded block is also available using a shortcode:

```
[sp-example-block title="Lorem ipsum"]
```

*Requires: [Advanced Custom Fields Pro](https://www.advancedcustomfields.com/)*

### Templates

This module will register a template custom post type and replaces default theme templates by searching for template slugs. This allows your client to edit these pages using the WordPress editor and without changing theme code.

Examples:
  * A template with slug `404` will replace the default 404 template.
  * A template with slug `home` will replace the default blog posts template.
  * A template with slug `search` will replace the default search page template.
  * A template with slug `product` will replace the default product page template.
  * A template with slug `{post-type}` will replace the default single post type template.
  * A template with slug `archive-{post-type}` will replace the default post type archive template.

Add the following filter to your theme to activate this module:

```php
add_filter('sp_templates_enabled', '__return_true');
```

### Filters

#### Branding

* `sp_branding_name` - string - the branding name.
* `sp_branding_logo` - string - the branding logo url.
* `sp_branding_support_url` - string - the branding support url.
* `sp_branding_support_email` - string - the branding support email.
* `sp_branding_support_widget` - string - the branding support widget script.
* `sp_branding_login_enabled` - boolean - replace the default WordPress login logo with the branding logo.
* `sp_branding_wp_head_enabled` - boolean - add a powered by text to the site's `<head>`.
* `sp_branding_admin_bar_enabled` - boolean - remove the WordPress logo from the admin bar.
* `sp_branding_admin_footer_enabled` - boolean - add a powered by text to the admin footer.

#### Appearance

Register CSS variables which you can use in your stylesheets. Registered colors are also added to the WordPress block editor.

* `sp_color_primary` - array(name, color) - the primary theme color (CSS variable: `--sp-color-primary`).
* `sp_color_secondary` - array(name, color) - the secondary theme color (CSS variable: `--sp-color-secondary`).
* `sp_color_third` - array(name, color) - the third theme color (CSS variable: `--sp-color-third`).
* `sp_color_fourth` - array(name, color) - the fourth theme color (CSS variable: `--sp-color-fourth`).
* `sp_container_width` - string - the theme's max container width (CSS variable: `--sp-container-width`).

#### Other

* `sp_hide_recaptcha_badge` - boolean - hide reacaptcha badge.
