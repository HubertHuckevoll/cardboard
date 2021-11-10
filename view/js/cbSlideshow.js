"use strict";

class cbSlideshow extends cbBase
{
  constructor(slidesSelector, displayTime)
  {
    super();

    this.timerID = null;
    this.statusPlay  = '(Anklicken des Bildes pausiert Slideshow)';
    this.statusPause = '(Pausiert)';

    this.slides = document.querySelectorAll(slidesSelector);
    if (this.slides.length > 0)
    {
      this.displayTime = displayTime;
      this.pauseSlide = false;
      this.first = this.slides[0];
      this.parent = this.slides[0].parentNode;
    }
  }

  init()
  {
    if (this.slides.length > 0)
    {
      let statusEl = document.createElement('div');
      statusEl.setAttribute('id', 'slideStatus');
      statusEl.innerText = this.statusPlay;
      this.parent.appendChild(statusEl);

      this.slides.forEach((slide) =>
      {
        slide.querySelector('img').addEventListener('click', this.cTogglePause.bind(this));
      });

      this.slides.forEach((slide) =>
      {
        this.hide(slide);
        slide.setAttribute('data-active', 'no');
      });
      this.show(this.first);
      this.first.setAttribute('data-active', 'yes');

      this.cNextImage(this.displayTime);
    }
  }

  cTogglePause(ev)
  {
    if (this.pauseSlide == false)
    {
      clearTimeout(this.timerID);
      this.timerID = null;
      this.pauseSlide = true;
      document.querySelector('#slideStatus').innerHTML = this.statusPause;
    }
    else
    {
      this.pauseSlide = false;
      document.querySelector('#slideStatus').innerHTML = this.statusPlay;
      this.cNextImage(0);
    }
  }

  cNextImage(time)
  {
    this.timerID = setTimeout(() =>
    {
      let current = document.querySelector('.slide[data-active="yes"]');
      let next = current.nextElementSibling;

      if (next.querySelectorAll('img').length === 0)
      {
        next = this.first;
      }
      current.setAttribute('data-active', 'no');

      this.fadeOut(current, () =>
      {
        next.setAttribute('data-active', 'yes');
        this.fadeIn(next);
      });

      if (!this.pauseSlide)
      {
        this.cNextImage(this.displayTime);
      }
    }, time);
  }

}
