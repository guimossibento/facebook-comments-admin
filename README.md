#ADMIN SYSTEM OF AUTOMATIC COMMENTS

##This project use websockets at Dashboard and Commnet Log pages

##Things to improve
- boostrap.js needs to working at localhost dynamically
- To improve the security, it'll better switch the public channel to Private channel.
- Test all deactivated accounts

##"Bugs" to fix
- Now the Niche is attached at Comment Log by text, so needs to use nicheId as link and refactor all code where use it
- Change Event folder to infrastructure folder, now it works only with default path, however it needs to work with broadcastAs

##Dependencies
- .env needs to have a puppeteer API token
- .env needs to have a websocket key and password
- .env need to have a certificate if it needs to use SSL
