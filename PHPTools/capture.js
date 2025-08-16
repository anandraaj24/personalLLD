const puppeteer = require('puppeteer');
const fs = require('fs');

const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // Load cookies
  const cookiesJson = fs.readFileSync('cookies.json', 'utf-8');
  const cookies = JSON.parse(cookiesJson);
  await page.setCookie(...cookies);

  // Get CLI args
  const url = process.argv[2];
  const questionImage = process.argv[3];
  const answerImage = process.argv[4];

  await page.goto(url, { waitUntil: 'load', timeout: 60000 });

  // Hide header/left/bottom panels
  await page.evaluate(() => {
    const element = document.querySelector('.qa-header');
    const leftPanel = document.querySelector('.leftPanel');
    const bottomBarContent = document.querySelector('.bottom-bar-content');
    if (element) element.style.display = 'none';
    if (leftPanel) leftPanel.style.display = 'none';
    if (bottomBarContent) bottomBarContent.style.display = 'none';
  });

  try {
    await page.waitForSelector('.qa-q-view-main form');
    await page.waitForSelector('article.qa-a-list-item');

    // Capture question
    const questionDiv = await page.$('.qa-q-view-main form');
    if (questionDiv) {
      await questionDiv.screenshot({ path: questionImage });
    }

    // Find the most upvoted answer
    const bestAnswer = await page.evaluateHandle(() => {
      const answers = [...document.querySelectorAll('article.qa-a-list-item')];
      if (!answers.length) return null;

      let best = null;
      let maxVotes = -Infinity;

      answers.forEach(ans => {
        const voteSpan = ans.querySelector('.qa-netvote-count-data');
        const votes = voteSpan ? parseInt(voteSpan.innerText.trim(), 10) : 0;
        if (votes > maxVotes) {
          maxVotes = votes;
          best = ans;
        }
      });

      return best;
    });

    if (bestAnswer) {
      const formInside = await bestAnswer.$('.qa-a-item-main form');
      if (formInside) {
        await formInside.screenshot({ path: answerImage });
      } else {
        await bestAnswer.screenshot({ path: answerImage });
      }
    }

  } catch (e) {
    console.error('Error capturing page:', url);
    console.error(e.message);
  }

  await browser.close();
})();
