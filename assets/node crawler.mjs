import puppeteer from 'puppeteer';

const url = "https://smart-tribune-sandbox.ovh/subdomain/hackathon-laplateforme-2023/faq-site-down.html"

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  await page.goto(url);

  const error = await HTTPRequest.status();
  

  console.log(error)

  if(error == "404")
  {
    console.log('site not found');
  }
})