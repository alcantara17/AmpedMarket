#What is AmpedMarket
AmpedMarket is an open source project forked from BitWasp which aims to allow people to quickly set up anonymous markets with integrated BitCoin management and escrow services. AmpedMarket is designed to be run as a Tor hidden service. As such it is developed with security and anonymity in mind.

This project is still in its very early stages. It is essentially BitWasp until it has been thoroughly document and verified, at which point changes will be made. Minor patches to existing components will be pushed back to BitWasp, however new/highly modified components will remain unique to this project (others can use the code in their own software if they wish). 

Development supported by 58-08-2, 64-17-5, 1972-08-3 and of course 300-62-9 :D

#Want To Get Involved?

Use the wiki on github if you want to get involved in the development, or send an email to amphetamine@tormail.org

Even just testing on different configurations can be a big help.

For a list of tasks related to AmpedMarket, please visit https://github.com/ampedup/AmpedMarket/wiki/Tasks

#Current High-priority Development Activities
- Reverse Engineering Documentation From BitWasp Source 
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Reverse-Engineering-Documentation-From-BitWasp-Source)
- Audit Security Of CodeIgniter Components
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Audit-Security-Of-CodeIgniter-Components)
- Define Supported Development Environments
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Define-Supported-Development-Environments)

#Security TODO List (don't use this software unless this list is empty!)
- Audit Security Of CodeIgniter Components
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Audit-Security-Of-CodeIgniter-Components)
- Implement Base64 Encoding Instead Of Escaping For String Data In SQL Drivers
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Implement-Base64-Encoding-Instead-Of-Escaping-For-String-Data-In-SQL-Drivers)
- Audit Anti OCR Features Of CodeIgniter CAPTCHA Generation
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Audit-Anti-OCR-Features-Of-CodeIgniter-CAPTCHA-Generation)
- Audit Security Of Bitwasp Components
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Audit-Security-Of-BitWasp-Components)
- Define Penetration Testing Criteria
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Define-Penetration-Testing-Criteria)

#Testing TODO List
- Define Supported Testing Environments
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Define-Supported-Testing-Environments)
- Define Supported Production Environments
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Define-Supported-Production-Environments)
- Define Penetration Testing Criteria
 - (https://github.com/ampedup/AmpedMarket/wiki/Task:-Define-Penetration-Testing-Criteria)

#Planned New Features
- Extract Vendor Information From Third Party Marketplaces
 - (https://github.com/ampedup/AmpedMarket/wiki/Feature:-Extract-Vendor-Information-From-Third-Party-Marketplaces)

#Installation and Configuration (At this stage identical to BitWasp)
To set up AmpedMarket, first make ./application/config and ./assets/images writable.

chmod 777 ./application/config
chmod 777 -R ./assets/images

And then visit the installer page to set up the marketplace.

# Support AmpedMarket Development
All money from donations go to fund AmpedMarket development. 
Bitcoin Address: 14B1cC1gMHYbAAoGMMyfiYNJDBQpeMTUz1

These contributions help out a great deal and allow for faster development with more thorough testing (which we take very seriously)

