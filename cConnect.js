
// in : username, password, url, search terms
// out: json with projects and info 



 // const Declarations

 const mainUrl= "https://login.cmdgroup.com/Account/Login?returnUrl=https%3A%2F%2Finsight.cmdgroup.com%2FSingleSignOn%2FRedirectToModule&immediate=false"



 const fs = require("fs");
 
 const puppeteerExtra = require('puppeteer-extra');
 const pluginStealth = require('puppeteer-extra-plugin-stealth');
 puppeteerExtra.use(pluginStealth());
 
 const puppeteer = require('puppeteer');
 let cheerio = require('cheerio');
 const { text } = require('cheerio/lib/api/manipulation');
 const { exit } = require("process");
 const { exec } = require('child_process');
 
 
 const primaryKeywords = [
   "Solar",
   "Nanawall",
   `"Metal Framed Skylights"`,
   "Lacantina",
   "Greenhouses",
   "Panda",
   `"Wasco Skylight Competitors"`,
   "Supersky",
   "Euro-Wall",
   `"Folding Glass Wall"`,
   `"Wood Curtain Walls"`,
   `"Tilt Turn Windows"`,
   "Acurlite",
   "Florian",
   `"Areo Nano"`
 ]
 
 
 
 const cats = [
   "CheckBox",
   "Star",
   "ProjectTitle",
   "MatchingDocuments",
   "Stadge",
   "BidDate",
   "ProjectValue",
   "SubCategory",
   "BidTime",
   "City",
   "State",
   "Role",
   "CompanyName",
   "ContactName",
   "Phone",
   "Email",
   "UserName",
   "CreateDate",
   "Note",
   "Private",
   "List Date"
 
 ]
 
 /// Var Variable Declarations 
 
 
 var primaryIndex = 9 ;
 var catIndex = 0 ;
 var project = {}
 var datedProjects = []
 var SRURL; 
 var projectUrls = []
 var jsonProjects = [] 
 // main function 
 const main = async() =>{
       
           var results = []
           var projects = []
           var projectIndex= 0 
           const browser = await puppeteerExtra.launch({headless: false});
           const page = await browser.newPage();
           await page.goto(mainUrl);
         
           await page.type("#txtUserName", "Skylight@solarinnovations.com")
           await page.type("#txtPassword", "Solar!Rules1")
           await page.click('#btnLogon')
 
           await page.waitForNavigation();
           await page.click("#search-trigger")
           await page.click(".i-specs")
           
 
           await page.type('#demo-input-local', process.argv[2])
           await page.keyboard.press("Enter");
           await page.waitForTimeout(5000)
           SRURL = await page.url()
           await page.waitForSelector('.x-grid-item-container')
           
           .then(() => {
               const content = page.content();
               content
                   .then((success) => {
                       const $ = cheerio.load(success)
 
                         // gets Table id numbers and adds them to Projects.json 
 
                       $('.x-grid-item-container > table').each((index, element) => {
                       
                         if(index <= 2){
                           return;
                         }   
                         project= {}
                         // console.log($(element).attr('id') )
                         project.tableId=$(element).attr('id') 
                         projects.push(project)
                         //testing break point
                         // if(index > 5 ){
                         //   return false;
                         // }
                       });
                         //
 
                           // loops through individual tables and creates objects from the cat list 
                       $('.x-grid-item-container > table > tbody > tr > td ').each((index, subElement) => {
                         if(index <= 2){
                           return;
                         } 
 
 
                           if(catIndex <20){
                             // console.log(` Project: ${projectIndex}  Cat: ${cats[catIndex]}  Text: ${$(subElement).text()}`)
 
                             if($(subElement).text() !== undefined){
                               try{
                               projects[projectIndex][cats[catIndex]] = $(subElement).text()
 
                               }catch(error){
                                 console.error(error)
                                 projects[projectIndex][cats[catIndex]]= "N/A"
 
                               }
 
                             }
                             else{
                               projects[projectIndex][cats[catIndex]]= "N/A"
                             }
                           
                             catIndex+=1;
                           }
                           else{
                             catIndex = 0  
                             projectIndex+=1;
                             // projects.push(project)
                             // project = {}
                           }
                             // close 
 
                       
                     });
                       fs.writeFileSync('./projects.json', JSON.stringify(projects))
                       console.log('wrote projects to /projects.json')
               
             // Removes all postdated projects from Projects.json
 
 
               try{
                                jsonProjects = JSON.parse(fs.readFileSync('./projects.json'))
 
               }
               catch(error){
                 console.log(error)
                 reset();
               }
          
               var goodCount = 0
               var badCount = 0 
 
               for( x in jsonProjects){
                     let projectDate = jsonProjects[x].BidDate
                     let today = new Date();
                     let other = new Date(projectDate)
                     // console.log(projectDate)
                     if(other> today){
                       datedProjects.push(jsonProjects[x])
                       goodCount +=1 
                     }
                     else{
                       badCount +=1
                     }
 
 
               }
               
               fs.writeFileSync('./projects.json', JSON.stringify(datedProjects))
               console.log(` Bids after Today: ${goodCount}, Other: ${badCount}`)
               // close 
           })
   });
 
 
     // Have a list of projects - JsonProjects, Need to grab Documents from Site 
     let jsonProjects = JSON.parse(fs.readFileSync('./projects.json'))
         console.log(jsonProjects.length)
         for(x in jsonProjects){
           console.log("starting child_process: " + x + "..." +"title: "+ jsonProjects[x].ProjectTitle)
           exec( `node getDocs.js "${ jsonProjects[x].ProjectTitle }"`);
         }
     
 
 
 
 }
 
 main();
 
 
 
 const reset = async ()=>{
   var results = []
   var projects = []
   var projectIndex= 0 
     const browser = await puppeteerExtra.launch({headless: false});
   const page = await browser.newPage();
   await page.goto(mainUrl);
  
   await page.type("#txtUserName", "Skylight@solarinnovations.com")
   await page.type("#txtPassword", "Solar!Rules1")
   await page.click('#btnLogon')
 
   await page.waitForNavigation();
 
 
   await page.type('#demo-input-local', primaryKeywords[primaryIndex])
   await page.keyboard.press("Enter");
   await page.waitForTimeout(5000)
   SRURL = await page.url()
   await page.waitForSelector('.x-grid-item-container')
   
   .then(() => {
       const content = page.content();
       content
           .then((success) => {
               const $ = cheerio.load(success)
 
                 // gets Table id numbers and adds them to Projects.json 
 
               $('.x-grid-item-container > table').each((index, element) => {
               
                 if(index <= 2){
                   return;
                 }   
                 project= {}
                 console.log($(element).attr('id') )
                 project.tableId=$(element).attr('id') 
                 projects.push(project)
                 //testing break point
                 // if(index > 5 ){
                 //   return false;
                 // }
               });
                 //
 
                   // loops through individual tables and creates objects from the cat list 
               $('.x-grid-item-container > table > tbody > tr > td ').each((index, subElement) => {
                 if(index <= 2){
                   return;
                 } 
 
 
                   if(catIndex <20){
                     console.log(` Project: ${projectIndex}  Cat: ${cats[catIndex]}  Text: ${$(subElement).text()}`)
 
                     if($(subElement).text() !== undefined){
                       try{
                        projects[projectIndex][cats[catIndex]] = $(subElement).text()
 
                       }catch(error){
                         console.error(error)
                         projects[projectIndex][cats[catIndex]]= "N/A"
 
                       }
 
                     }
                     else{
                       projects[projectIndex][cats[catIndex]]= "N/A"
                     }
                   
                     catIndex+=1;
                   }
                   else{
                     catIndex = 0  
                     projectIndex+=1;
                     // projects.push(project)
                     // project = {}
                   }
                     // close 
 
               
             });
           });
               fs.writeFileSync('./projects.json', JSON.stringify(projects))
 })
 }