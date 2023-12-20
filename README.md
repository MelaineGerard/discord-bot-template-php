
# Discord Bot Template

This repository contains a discord bot template in PHP


## Authors

- [@MelaineGerard](https://www.github.com/melainegerard)


## Features

- Database support thanks to Doctrine ORM
- CLI commands to easily generate commands and events
- Slash Command Support
- Auto import Slash Command into Discord on start


## Installation

- Install the project

```bash
# Cloning the project
git clone git@github.com:MelaineGerard/discord-bot-template-php.git
cd discord-bot-template-php

# Install dependencies
composer Install

cp .env.example .env
```

Update the .env with your informations (Discord bot token, ...)


- Start the project
```bash
php public/index.php
```
## License

[MIT](https://choosealicense.com/licenses/mit/)

