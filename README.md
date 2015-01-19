AWS Extension for Yii 2
===========================

This extension allows the Yii2 framework to communicate with [Amazon Web Servies](http://aws.amazon.com/).


Installation
------------

This extension can be install via Composer. In your root project directory composer.json can be modified to include:
    ...
    "require-dev": {
        ...
        "jambroo/yii2-aws": "*"
    },
    "repositories": [
    {
        "type": "package",
        "package": {
            "name": "jambroo/yii2-aws",
            "version": "1.0",
            "source": {
	        "url": "https://github.com/jambroo/yii2-aws.git",
                "type": "git",
                "reference": "master"
            }
        }
    }],
    ...

The 'composer install' or 'composer update' command will then need to be run.


General Usage
-------------

To run the supplied Codeception unit tests please navigate to the root directory of this plugin and run:
			<project dir>/vendor/bin/codecept build
			<project dir>/vendor/bin/codecept --steps --debug --verbose  run unit