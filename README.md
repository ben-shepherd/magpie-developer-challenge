## BENJAMIN'S NOTES

- Entry point starts at src/entry.php. Usage is noted at the bottom.

- I have created the folder structure to be quite modular that would allow for large scale applications, keeping each class and method as simple as possible as to not cross contaminate concerns

- The scraper has a base abstract class that in theory could be used for many different types of scrapers, typically for a large scale product I would try to make things generic as possible so they can be adapted to many different types of logic but return a familiar and predictable result

- In the real world, a lot of the configuration I have hard coded would likely be better coming from an external source (db), this will make quick fixes easier and editable by anyone providing there's some UI to do so.

- The data extractor (src\Utils\Date\DateExctractor.php) is extremely far from perfect. I'm aware it has flaws and won't catch every single use case. If I had more time, I would build a super generic function that could take any date format, and using each value/part/position to determine it's likely value type, then dynamically generate the php date format string based on the findings to eventually resolve them into a standardised format 

- The contents in output.json has more information than requeired, some of this was used for creating the logic, and some for debugging. If we only wanted certain information to be visible, a presentation layer could be added to only return the contents required

## Magpie PHP Developer Challenge

Your task is to gather data about the products listed on https://www.magpiehq.com/developer-challenge/smartphones

The final output of your script should be an array of objects similar to the example below:

```
{
    "title": "iPhone 11 Pro 64GB",
    "price": 123.45,
    "imageUrl": "https://example.com/image.png",
    "capacityMB": 64000,
    "colour": "red",
    "availabilityText": "In Stock",
    "isAvailable": true,
    "shippingText": "Delivered from 25th March",
    "shippingDate": "2021-03-25"
}

```

You should use this repository as a starter template.

You can refer to the [Symfony DomCrawler](https://symfony.com/doc/current/components/dom_crawler.html) documentation for a nice way to capture the data you need.

Hint: the `Symfony\Component\DomCrawler\Crawler` class,  and its `filter()` method, are your friends.

You can share your code with us by email, or through a service like GitHub.

Please also send us your `output.json`.

### Notes
* Please de-dupe your data. We don’t want to see the same product twice, even if it’s listed twice on the website.
* Make sure that all product variants are captured. Each colour variant should be treated as a separate product.
* Device capacity should be captured in MB for all products (not GB)
* The final output should be an array of products, outputted to `output.json`
* Don’t forget the pagination!
* You will be assessed both on successfully generating the correct output data in `output.json`, and also on the quality of your code.

### Useful Resources
* https://symfony.com/doc/current/components/dom_crawler.html
* https://symfony.com/doc/current/components/css_selector.html
* https://github.com/jupeter/clean-code-php

### Requirements

* PHP 7.4+
* Composer
* Docker (Optional if your system already has PHP installed)

### Setup

```
git clone https://github.com/ben-shepherd/magpie-developer-challenge

cd magpie-developer-challenge

docker-compose up
```

To run the scrape you can use:

```
docker exec -it magpie-app sh

php src/entry.php
```
