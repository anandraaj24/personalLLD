Goal is to Make the content(text+assets+styling+layout+js animations) of above the fold available as soon as possible and interactive(css+js animations) at the same time and the layout should not be shifted too much, it should be fixed and the interaction time of animations should be as fast as possible.

css:
preload css for above the fold.
minify all the css and load lazy.
purge and remove unused css.
critical css for above the fold content

images:
preload for LCP and lazy load for other images
srcset and alt attributes for images.
image compression to webP format.
Dimension should be as needed.
Rendering should not be blocked due to css or js.

font:
preload font for above the fold
include font in-page the html <style> tag.

preloading methods:
preload: For critical assets like CSS, fonts, or JavaScript that are needed immediately for rendering.
dns-prefetch: Prefetch DNS resolution for external domains.
prefetch: For non-essential assets that will be used later.
preconnect: For external resources to reduce connection latency.
prerender: For full pages or content that you expect the user to visit shortly.Caching: Combine preloading with caching strategies for better resource management.

javascript:
preload js for above the fold
include js in-page for above the fold.
execution for js above the fold should not be blocked.
delay all other javascript which are not necessary for above the fold
defer or async other javascript.
it should not block any kind of css or image rendering.
javascript animation should be as fast as possible.
use setTimeout for delaying and defer keyword for deferring.
the animation update+paint logic should be executed first then all other logic in the background.

monitor text to html ratio of the page.

use rendering method which is best for your page and which makes the page speed more faster.

use service workers to cache page + for offline use.



Preloading Javascript method

1. Using the <link rel="preload"> Tag
The most common way to preload JavaScript is by using the <link rel="preload"> tag within the <head> section of your HTML document. This tells the browser to load the JavaScript file early in the page load process, without blocking the rendering of the rest of the page.
Example:
html
CopyEdit
<head>
  <link rel="preload" href="your-script.js" as="script">
</head>
Explanation:
* href="your-script.js": Path to the JavaScript file you want to preload.
* as="script": Specifies the type of resource being preloaded (in this case, it’s a script).
Benefits:
* This ensures that the browser starts fetching the JavaScript file early, but it won't execute the script immediately. The script will be ready when it’s needed, improving performance by reducing the delay when the script is called.
2. Using the async or defer Attributes
When loading JavaScript files, you can use the async or defer attributes to improve page load times. While not strictly “preloading,” these attributes control when and how the script is executed relative to the document’s parsing.
async Example:
html
CopyEdit
<script src="your-script.js" async></script>
* async: The script is fetched asynchronously and executed as soon as it's available, without blocking HTML parsing. This is useful for scripts that are independent of other content (e.g., analytics or third-party scripts).
defer Example:
html
CopyEdit
<script src="your-script.js" defer></script>
* defer: The script is fetched asynchronously, but it is executed after the HTML parsing is complete. This is useful for scripts that rely on the DOM being fully loaded before they execute.
3. Preloading Specific Resources in JavaScript
If you want to preload JavaScript dynamically using JavaScript itself, you can create a new <link> element and add it to the document’s <head> section:
Example:
javascript
CopyEdit
var link = document.createElement('link');
link.rel = 'preload';
link.href = 'your-script.js';
link.as = 'script';
document.head.appendChild(link);
This will preload the JavaScript file just as if you used the <link> tag directly in the HTML.
4. Using Preload with Module Scripts
For ES6 module scripts, you can also preload them using the <link rel="preload"> tag, but ensure you define the type="module" in the script tag.
Example:
html
CopyEdit
<head>
  <link rel="preload" href="module-script.js" as="script" type="module">
</head>
5. Avoiding Redundant Preloads
You should avoid preloading the same JavaScript file multiple times, as it can waste bandwidth and cause performance issues. Also, be careful about preloading too many resources at once, as it can overwhelm the browser’s network capacity.
6. Combine with Code Splitting (for Larger Websites)
If your website has many JavaScript files, you can use a technique called code splitting to break up large scripts into smaller files that are only loaded when needed. This can be combined with preloading to ensure critical scripts are ready early, but non-essential ones are only loaded when the user needs them.
* Code splitting tools: If you're using a build tool like Webpack, Parcel, or Vite, you can set up code splitting and preload important chunks using their configuration options.
7. Browser Caching
Along with preloading, make sure your JavaScript files are cached effectively. This can be done by setting proper cache headers or versioning your scripts (e.g., your-script.js?v=1.0.0).
8. Using Content Delivery Networks (CDNs)
If you are loading third-party libraries (e.g., React, jQuery), consider preloading them from a CDN. CDNs generally offer faster load times due to their distributed servers.
Example:
html
CopyEdit
<head>
  <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/react/17.0.2/umd/react.production.min.js" as="script">
</head>
9. Preload JavaScript Files for Critical Interaction
If a JavaScript file is required for critical interaction (e.g., for opening a modal or submitting a form), preload it early in the <head> section so that it is ready when the user interacts with the page.


Ways to break long tasks in js
https://macarthur.me/posts/long-tasks/

Async Await Method
setTimeout()
Scheduler API
requestIdleCallback()requestAnimationFrame()
yeildToMain() — await with scheduler API and fallback to setTimeout;
Web Workers


```javascript
async function runJobs(jobQueue, deadline=50) { 
	let lastYield = performance.now(); 
	for (const job of jobQueue) { 
		// Run the job: 
		job(); 
		// If it's been longer than the deadline, yield to the main thread: 
		if (performance.now() - lastYield > deadline) { 
			await yieldToMain(); lastYield = performance.now(); 
		}
	} 
} 
function yieldToMain () {
	if (globalThis.scheduler?.yield) {
			return scheduler.yield();
	} 
	// Fall back to yielding with setTimeout. 
	return new Promise(resolve => { setTimeout(resolve, 0); }); 
}
```


For UI Updates:
* UI updates (such as DOM manipulations, animations, or input handling) should generally happen in a way that minimizes blocking the main thread to ensure that the browser can update the UI smoothly and respond to user interactions quickly.
* If your UI updates involve heavy work, you may need to yield to the main thread periodically to prevent the browser from freezing or becoming unresponsive.
In other words, UI updates need to be responsive and should not block. If you have long-running UI updates (e.g., adding many elements, animations, etc.), you can break them into chunks and yield control between those chunks to allow the browser to handle other tasks (such as rendering and responding to user input).
For Background Jobs:
* Background jobs (such as complex calculations, fetching data from an API, or other non-UI tasks) should also avoid blocking the main thread as much as possible.
* If these background tasks involve heavy computation or processing, they should not block the main thread for too long, because that will prevent the browser from updating the UI and responding to user interactions.
* For background jobs, you can offload the work to Web Workers (which run in parallel with the main thread) or, if it's not feasible to use Web Workers, yield control periodically during the job to allow the main thread to process UI events.
Key Principles:
1. Heavy work that affects the UI should yield to the main thread periodically to keep the interface responsive. For example, if you’re updating a list or applying a series of animations, breaking the work into chunks with yield helps prevent UI jank (stuttering) and ensures smooth interactions.
2. For background jobs that don’t directly manipulate the UI, it’s still important to yield periodically to avoid blocking the main thread, particularly when you're doing something intensive (like processing large datasets). If you can, offload heavy work to Web Workers to prevent blocking the main thread completely.



￼
Script Evaluation — time taken while executing the javascript
Other — Loading of resource, network requests
Style & layout — time taken to recalculate the style changes and repainting — reduce frequent css changes.