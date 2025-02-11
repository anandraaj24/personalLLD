// Remove inline styles
document.querySelectorAll('*').forEach(function(element) {
    element.removeAttribute('style');
});

// Remove external and embedded styles
document.querySelectorAll('link[rel="stylesheet"], style').forEach(function(element) {
    element.remove();
});

// apply css.
var style = document.createElement('style');
style.type = 'text/css';
style.innerHTML = `

`;
document.head.appendChild(style);

