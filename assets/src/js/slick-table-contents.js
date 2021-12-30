import Alpine from 'alpinejs';


document.addEventListener('alpine:init', () => {
    Alpine.data('toc', (selector, headingSelection, listStyle) => ({
      
      init() {
        var content = document.querySelector('.entry-content');
        var headings = content.querySelectorAll(`${headingSelection}`);
        var headingMap = {};
        
        Array.prototype.forEach.call(headings, function (heading) {
          var id = heading.id ? heading.id : heading.textContent.trim().toLowerCase()
            .split(' ').join('-').replace(/[!@#$%^&*():]/ig, '');
          headingMap[id] = !isNaN(headingMap[id]) ? ++headingMap[id] : 0;
          if (headingMap[id]) {
            heading.id = id + '-' + headingMap[id];
          } else {
            heading.id = id;
          }
        });
  
        window.tocbot.init({
          // Where to render the table of contents.
          tocSelector: `#${selector}`,
          // Where to grab the headings to build the table of contents.
          contentSelector: '.entry-content',
          // Which headings to grab inside of the contentSelector element.
          headingSelector: `${headingSelection}`,
          ignoreSelector: '.ignore',
          // For headings inside relative or absolute positioned containers within content.
          hasInnerContainers: true,
          orderedList: listStyle
        });  
      } 
    }));
  });

window.Alpine = Alpine;
Alpine.start();
