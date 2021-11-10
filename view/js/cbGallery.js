"use strict";

class cbGallery
{
  constructor(imagesSelector)
  {
    this.selImages = document.querySelectorAll(imagesSelector);
  }

  init()
  {
    if (this.selImages.length)
    {
      this.vInit();

      // Click on image = launch gallery overlay
      this.selImages.forEach((elem) =>
      {
        elem.addEventListener('click', this.cLaunch.bind(this));
      });

      // Hide progress whenever we're done loading the image
      document.querySelector('#cbGalleryImg').addEventListener('load', this.vHideProgress.bind(this));

      // Mouse
      document.querySelector('#cbGalleryPrev').addEventListener('click', this.cHandleMouse.bind(this));
      document.querySelector('#cbGalleryNext').addEventListener('click', this.cHandleMouse.bind(this));
      document.querySelector('#cbGalleryClose').addEventListener('click', this.cHandleMouse.bind(this));

      // Keyboard
      document.addEventListener('keyup', this.cHandleKeypress.bind(this));
    }
  }

  cLaunch(ev)
  {
    let imgEl = ev.target;
    let img = (imgEl.getAttribute('data-hires') != undefined) ? imgEl.getAttribute('data-hires') : imgEl.getAttribute('src');

    this.vOpen();
    this.vShowProgress();
    this.vShowImage(img);

    ev.preventDefault();
    return false;
  }

  cHandleKeypress(ev)
  {
    if (ev.code == 'ArrowLeft')
    {
      this.cNavigate('bwd');
    }

    if (ev.code == 'ArrowRight')
    {
      this.cNavigate('fwd');
    }

    if (ev.code == 'Escape')
    {
      this.vClose();
    }
  }

  cHandleMouse(ev)
  {
    let target = ev.target.getAttribute('id');

    switch (target)
    {
      case 'cbGalleryPrev':
        this.cNavigate('bwd');
      break;

      case 'cbGalleryNext':
        this.cNavigate('fwd');
      break;

      case 'cbGalleryClose':
        this.vClose();
      break;
    }

    ev.preventDefault();
    return false;
  }

  cNavigate(dir)
  {
    let curImg = document.querySelector('#cbGalleryImg').getAttribute('src');
    let imgs = [];
    let curIdx = undefined;
    let idx = undefined;
    let url = '';
    let i = 0;

    this.selImages.forEach((elem) =>
    {
      let href = (elem.getAttribute('data-hires') != undefined) ? elem.getAttribute('data-hires') : elem.getAttribute('src');

      imgs.push(href);
      if (href == curImg)
      {
        curIdx = i;
      }

      i++;
    });

    idx = curIdx;
    idx = (dir == 'bwd') ? idx - 1 : idx + 1;
    idx = (idx >= imgs.length) ? 0 : idx;
    idx = (idx < 0) ? (imgs.length-1) : idx;

    url = imgs[idx];
    this.vShowProgress();
    this.vShowImage(url);
  }

  vInit()
  {
    let rauchglasStyle = 'width: 100%;' +
                         'height: 100%;' +
                         'box-sizing: border-box;' +
                         'position: fixed;' +
                         'top: 0;' +
                         'left: 0;' +
                         'z-index: 9996;' +
                         'background-color: rgba(0, 0, 0, .9);';

    let imgBoxStyle = 'position: fixed;' +
                      'top: 0;' +
                      'left: 0;' +
                      'right: 0;' +
                      'bottom: 0;' +
                      'display: flex;' +
                      'align-items: center;' +
                      'justify-content: center;' +
                      'z-index: 9997;';

    let imgStyle = 'display: block;' +
                   'box-sizing: border-box;' +
                   'height: 100%;';

    let buttonStyles = 'font-size: 72pt;' +
                       'font-family: "Verdana";' +
                       'color: rgba(255, 255, 255, .1);' +
                       'font-weight: bold;' +
                       'text-decoration: none;';

    let navControlsStyle = 'position: fixed;' +
                           'top: 0;' +
                           'left: 0;' +
                           'right: 0;' +
                           'bottom: 0;' +
                           'display: flex;' +
                           'align-items: center;' +
                           'justify-content: space-between;' +
                           'z-index: 9998;';

    let closeBoxStyle = 'position: fixed;' +
                        'padding: 40px 25px 0 0;' +
                        'z-index: 9999;' +
                        'right: 0;' +
                        'top: 0;';

    let galleryEl = document.createElement('div');
    galleryEl.setAttribute('id', 'cbGallery');
    galleryEl.style.display = 'none';
    galleryEl.innerHTML = '<div id="cbGalleryRauchglas"></div>' +
                          '<div id="cbGalleryImgBox"><img id="cbGalleryImg"></div>' +
                          '<div id="cbGalleryNavControls">' +
                            '<a id="cbGalleryPrev" href="#">&laquo;</a>' +
                            '<progress max="100" style="display: none;"></progress>' +
                            '<a id="cbGalleryNext" href="#">&raquo;</a>' +
                          '</div>' +
                          '<div id="cbGalleryCloseBox"><a id="cbGalleryClose" href="#">X</a></div>';

    document.querySelector('body').appendChild(galleryEl);

    document.querySelector('#cbGalleryRauchglas').style.cssText = rauchglasStyle;
    document.querySelector('#cbGalleryImgBox').style.cssText = imgBoxStyle;
    document.querySelector('#cbGalleryPrev').style.cssText = buttonStyles;
    document.querySelector('#cbGalleryNext').style.cssText = buttonStyles;
    document.querySelector('#cbGalleryCloseBox').style.cssText = closeBoxStyle;
    document.querySelector('#cbGalleryClose').style.cssText = buttonStyles;
    document.querySelector('#cbGalleryNavControls').style.cssText = navControlsStyle;
    document.querySelector('#cbGalleryImg').style.cssText = imgStyle;
  }

  vShowProgress()
  {
    document.querySelector('progress').style.display = '';
    document.querySelector('#cbGalleryImg').style.border = '';
  }

  vHideProgress()
  {
    document.querySelector('#cbGalleryImg').style.border = '20px solid white';
    document.querySelector('progress').style.display = 'none';
  }

  vShowImage(url)
  {
    document.querySelector('#cbGalleryImg').style.display = 'none';
    document.querySelector('#cbGalleryImg').setAttribute('src', url);
    document.querySelector('#cbGalleryImg').style.display = '';
  }

  vOpen()
  {
    document.querySelector('#cbGallery').style.display = 'block';
  }

  vClose()
  {
    document.querySelector('#cbGallery').style.display = 'none';
  }
}