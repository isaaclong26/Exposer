

const mainUrl= "https://login.cmdgroup.com/Account/Login?returnUrl=https%3A%2F%2Finsight.cmdgroup.com%2FSingleSignOn%2FRedirectToModule&immediate=false"



const fs = require("fs");

const puppeteerExtra = require('puppeteer-extra');
const pluginStealth = require('puppeteer-extra-plugin-stealth');
puppeteerExtra.use(pluginStealth());

const puppeteer = require('puppeteer');
let cheerio = require('cheerio');
const { text } = require('cheerio/lib/api/manipulation');
const { exit } = require("process");



const main = async()=>{

var resultsCount
var keyword = process.argv[2]

// logs into REED 
const browser = await puppeteerExtra.launch({headless: false});
const page = await browser.newPage();
await page.goto(mainUrl);

await page.type("#txtUserName", "Skylight@solarinnovations.com")
await page.type("#txtPassword", "Solar!Rules1")
await page.click('#btnLogon')

await page.waitForNavigation();
//changes search option to Projects 
await page.click("#search-trigger")
await page.click(".i-title")


// types in search box and hits enter 
await page.type('#demo-input-local', keyword)
await page.keyboard.press("Enter");
await page.waitForTimeout(5000)




.then(async() => {
    const content = page.content();
    content
        .then(async (success) => {
            const $ = cheerio.load(success)
            // .then can only use cheerio here 
             resultsCount = parseInt($('#resultCount').text())


             if(resultsCount === 1){


                try{
                 await page.click(".x-grid-cell-colMatchingDocs")
                }
                catch(e){
                    console.log(e)
                        await page.click(".x-grid-cell-colProjectName")

                }
                finally{
                       await page.waitForNavigation();
                       await page.click("#projectDownloadDoc") 

                }
             }
            

             else{

             }
            


            //end of .then block
        }); });


}

main();