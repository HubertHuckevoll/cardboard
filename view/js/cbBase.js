"use strict";

/**
 * base class
 */

class cbBase
{
  /**
   * hide
   * ________________________________________________________________
   */
  hide(el)
  {
    el.style.display = 'none';
  }

  /**
   * show
   * ________________________________________________________________
   */
  show(el)
  {
    el.style.display = '';
  }

  /**
   * fadeOut
   * ________________________________________________________________
   */
  fadeOut(el, onSuccessFunc)
  {
    let whenInvisibleThen = function(ev)
    {
      el.style.display = 'none';
      el.style.transition = '';
      el.removeEventListener('transitionend', whenInvisibleThen);
      if (onSuccessFunc) onSuccessFunc();
    }

    el.addEventListener('transitionend', whenInvisibleThen);
    el.style.display = '';
    el.style.transition = 'opacity .75s ease-in-out';
    el.style.opacity = 0;
  }

  /**
   * fadeIn
   * ________________________________________________________________
   */
  fadeIn(el, onSuccessFunc)
  {
    let whenVisibleThen = function(ev)
    {
      el.style.transition = '';
      el.removeEventListener('transitionend', whenVisibleThen);
      if (onSuccessFunc) onSuccessFunc();
    }

    el.style.opacity = 0;
    el.style.display = '';
    el.clientWidth; // IMPORTANT HACK: force layout to ensure the new display: block and opacity: 0 values are taken into account when the CSS transition starts.
    el.style.transition = 'opacity .75s ease-in-out';
    el.addEventListener('transitionend', whenVisibleThen);
    el.style.opacity = 1;
  }
}