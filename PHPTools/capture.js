const puppeteer = require('puppeteer');

(async () => {
  // Launch headless browser
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // For setting cookies. Make 'cookies.json' file and put json cookies
  // const fs = require('fs');
  // const cookiesJson = fs.readFileSync('cookies.json', 'utf-8');
  // const cookies = JSON.parse(cookiesJson);
  // await page.setCookie(...cookies);

  // Set the URL from the command-line arguments
  const url = process.argv[2];  // Accept the URL from PHP via command-line
  const questionImage = process.argv[3]; // Get the dynamic question image filename
  const answerImage = process.argv[4]; // Get the dynamic answer image filename

  // Navigate to the URL
  await page.goto(url, { waitUntil: 'load', timeout: 60000 });

  await page.waitForSelector('.qa-header', { timeout: 60000 }); 
  await page.waitForSelector('.leftPanel', { timeout: 60000 }); 

  await page.evaluate(() => {
    // Hide the header container
    const element = document.querySelector('.qa-header');
    const leftPanel = document.querySelector('.leftPanel');
    if (element) {
      element.style.display = 'none';
      leftPanel.style.display = 'none';
    }
  });

  try {
    // Wait for the question and answer divs to load
    await page.waitForSelector('.qa-q-view-main form');  // Update with the actual selector for question div
    await page.waitForSelector('article.qa-a-list-item');    // Update with the actual selector for answer div

    // Capture screenshots of the question and answer divs
    const questionDiv = await page.$('.qa-q-view-main form'); // Adjust the selector
    if (questionDiv) {
      await questionDiv.screenshot({ path: questionImage });
    }

    const answerDiv = await page.$('article.qa-a-list-item .qa-a-item-main form'); // Adjust the selector
    if (answerDiv) {
      await answerDiv.screenshot({ path: answerImage });
    } else {
      const fallbackDiv = await page.$('article.qa-a-list-item .qa-a-item-main form');
      await fallbackDiv.screenshot({ path: answerImage });
    }

  } catch (e) {
    console.error('Error capturing the page: ', url);
    console.error('Error capturing the page: ', e.message);
  }

  // Close the browser
  await browser.close();
})();
