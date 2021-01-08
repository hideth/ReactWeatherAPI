# ReactWeatherAPI 

ReactWeatherAPI is a React/Symfony application that make a use of GoogleMaps and OpenWeatherAPI.

Applications uses two subPages, one is clickable GoogleMap that captures place of intrest, and statistic page, that shows checked places, with some statistics.

## Installation

### Prerequisites
To run application you need everything below:
- [Chrome Browser](https://www.google.com/chrome/)
- Chrome Extension [Allow CORS: Access-Control-Allow-origin](https://mybrowseraddon.com/access-control-allow-origin.html)
- ... or other solution to make local requests possible
- [Symfony CLI](https://symfony.com/download)
- [Composer](https://getcomposer.org/)
- Any kind of PHP/MySql solution, for example [WAMP](https://wampserver.aviatechno.net/)
- [Node](https://nodejs.org/en/)
- [Yarn](https://classic.yarnpkg.com/en/docs/install/#windows-stable)

## Installation:
### In order to install application run following:
```bash
composer install
yarn install
yarn build
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
php bin/console server:run

```
### After finishing testing app:

```bash
php bin/console doctrine:database:drop
```
## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)