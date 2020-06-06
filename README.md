# Laravel Forum Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/seongbae/discuss.svg?style=flat-square)](https://packagist.org/packages/seongbae/discuss)
[![Build Status](https://img.shields.io/travis/seongbae/discuss/master.svg?style=flat-square)](https://travis-ci.org/seongbae/discuss)
[![Quality Score](https://img.shields.io/scrutinizer/g/seongbae/discuss.svg?style=flat-square)](https://scrutinizer-ci.com/g/seongbae/discuss)
[![Total Downloads](https://img.shields.io/packagist/dt/seongbae/discuss.svg?style=flat-square)](https://packagist.org/packages/seongbae/discuss)

This package provides discussison forum for Laravel applications.  This is based on a Laravel forum tutorial I saw a while ago.  I wanted to put it into a pacakge for re-use and add some additional features such as receiving notifications for my own needs.

![Discuss screenshot](discuss-screenshot.png)

## Features

* Users can start a discussion thread and add replies.
* Users can edit or delete their own threads and replies. Admins can edit or delete any threads or replies.
* Threads can be categorized with channel.
* Users can subscribe to channels and get notified via email when a new thread is created in the channel.
* Users can subscribe to threads and get notified via email when replies are added.
* Thread view count is tracked.

## Installation

You can install the package via composer and publish assets:

```bash
composer require seongbae/discuss
php artisan vendor:publish --provider="Seongbae\Discuss\DiscussServiceProvider"
```

The package uses bootstrap-vue component.  Install it with following command:

```bash
npm install bootstrap-vue
```

Update your app.js to include following code:

```vue
import { BootstrapVue } from 'bootstrap-vue'
Vue.component('thread', require('./components/Thread.vue').default);
Vue.component('reply', require('./components/Reply.vue').default);
Vue.component('channel-subscribe', require('./components/ChannelSubscribe.vue').default);
Vue.use(BootstrapVue)
```

Then run npm commands:

```bash
npm install
npm run dev
```

## Usage

* After installation is complete, head over to /discuss.

### Libraries used

* This package uses sweetalert package by realrashid for displaying alerts. 

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email seong@lnidigital.com instead of using the issue tracker.

## Credits

- [Seong Bae](https://github.com/seongbae)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
