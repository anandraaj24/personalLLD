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