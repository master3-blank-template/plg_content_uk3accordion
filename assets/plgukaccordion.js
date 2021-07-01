/**
 * @package     Joomla.Plugin
 * @subpackage  Content.uk3accordion
 * @copyright   Copyright (C) Aleksey A. Morozov. All rights reserved.
 * @license     GNU General Public License version 3 or later; see http://www.gnu.org/licenses/gpl-3.0.txt
 */

document.addEventListener('DOMContentLoaded', function () {

  function plgUkAccordion_openSlide()
  {
    let anchor = window.location.hash.replace('#', '');
    let slide = document.getElementById(anchor);
    if (slide && slide.parentElement.classList.contains('plg_uk3accordion') == true) {
      if (slide.classList.contains('uk-open')) {
        return;
      }
      const index = (Array.prototype.slice.call(slide.parentElement.childNodes).indexOf(slide) + 1) / 2 - 1;
      UIkit.accordion(slide.parentElement).toggle(index);
    }
  }

  document.body.onclick = function(event) {
    setTimeout(() => {
      let anchor = window.location.hash.replace('#', '');
      let slide = document.getElementById(anchor);
      if (slide && slide.parentElement.classList.contains('plg_uk3accordion')) {
        if (event.target.parentElement.parentElement == slide.parentElement) {
          return;
        }
        event.preventDefault();
        const index = (Array.prototype.slice.call(slide.parentElement.childNodes).indexOf(slide) + 1) / 2 - 1;
        UIkit.accordion(slide.parentElement).toggle(index);
      }
    }, 100);
  };

  window.addEventListener('hashchange', function() {
    setTimeout(() => {
      plgUkAccordion_openSlide();
    }, 100);
  })

  plgUkAccordion_openSlide();

});
