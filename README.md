InMock
======

[![Build Status](https://travis-ci.org/GabrielAnca/inmock.svg?branch=master)](https://travis-ci.org/GabrielAnca/inmock)
[![Test Coverage](https://codeclimate.com/github/GabrielAnca/inmock/badges/coverage.svg)](https://codeclimate.com/github/GabrielAnca/inmock/coverage)
[![Issue Count](https://codeclimate.com/github/GabrielAnca/inmock/badges/issue_count.svg)](https://codeclimate.com/github/GabrielAnca/inmock)
[![Code Climate](https://codeclimate.com/github/GabrielAnca/inmock/badges/gpa.svg)](https://codeclimate.com/github/GabrielAnca/inmock)

InMock is a powerful, flexible and easily maintainable HTTP mocking library built in PHP. It aims to provide developers the ability to isolate any API, no matter the complexity, making it very easy to maintain and to make changes in the future.

InMock has all the features from any mocking library, but it is different because:
* It benefits of [Twig template engine](https://twig.symfony.com), allowing you to standardize your responses and objects and easily make changes.
* The responses, templates and routes can be easily tracked by version control, so your configuration is completely independent from InMock source code.
* Using it does not require any PHP knowledge.

**Attention**: This project is still in development phase, use it at your own risk. If you find any issue please [create a new issue](https://github.com/GabrielAnca/inmock/issues) or file a pull request.

# Installation

Installation documentation pending.

# Getting started

The first step is to define your routes file either in JSON format. The default location is `mocks/routes.json`. Let's define one endpoint to start with:

```json
{
  "routes": [
    {
      "endpoint": {
        "path": "/cats",
        "method": "get"
      },
      "response": {
        "template": "get_cats.json.twig",
        "statusCode": 200
      }
    }
  ]
}
```

InMock listens to any URL that you make a request to. In every request, it compares it to the endpoints specified in your routes file. It will render the template specified in the first endpoint that matches the request.

The next step is to define a template, for example `mocks/templates/get_cats.json.twig`:

```json
{
  "cats": [
    {
      "name": "Kitty",
      "sex": "female",
      "age": 4,
      "weight": 2.95
    },
    {
      "name": "Garfield",
      "sex": "male",
      "age": 1,
      "weight": 1.5
    }
  ]
}
```

Finally, InMock needs to know where the routes file and the templates folders are. For that, create a `parameters.yml` file based on the [parameters.yml.dist](app/config/parameters.yml.dist) file.

Then, you just need to make a request to your webserver, ie http://localhost/cats.
