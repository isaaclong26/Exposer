const fs = require("fs");

const puppeteerExtra = require('puppeteer-extra');
const pluginStealth = require('puppeteer-extra-plugin-stealth');
puppeteerExtra.use(pluginStealth());

const puppeteer = require('puppeteer');
let cheerio = require('cheerio');
const { text } = require('cheerio/lib/api/manipulation');
const { exit } = require("process");

const main = async() => {
var mainUrl = "https://insight.cmdgroup.com/SearchResult/ProjectSearchResult/Index"
const browser = await puppeteerExtra.launch({headless: false});
const page = await browser.newPage();
await page.goto(mainUrl);

}
main();