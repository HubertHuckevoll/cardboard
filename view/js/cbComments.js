"use strict";

class cbComments
{
  constructor(commentsBoxSelector)
  {
    this.commentsBox = document.querySelector(commentsBoxSelector);
  }

  init()
  {
    if (this.commentsBox)
    {
      this.commentsBox.querySelector('a#commentsShow').addEventListener('click', this.cHandleClick.bind(this));
      this.commentsBox.querySelector('a#commentsAdd').addEventListener('click', this.cHandleClick.bind(this));
    }
  }

  cHandleClick(ev)
  {
    let targetID = ev.target.getAttribute('id');
    let ccb = this.commentsBox.querySelector('#commentsContentBox');
    let url = ev.target.getAttribute('data-ajax-href');

    url = url + '&ajax=ajax';
    ccb.style.display = 'none';

    fetch(url).then(response => response.text()).then((data) =>
    {
      ccb.innerHTML = data;
      ccb.style.display = '';
      if (targetID == 'commentsAdd')
      {
        ccb.querySelector('#commentsForm').addEventListener('submit', this.cHandleSubmit.bind(this));
      }
    });

    ev.preventDefault();
    return false;
  }

  cHandleSubmit(ev)
  {
    let url = ev.target.getAttribute('data-ajax-action');

    url = url +
          '&ajax=ajax' +
          '&email='+document.getElementById('email').value +
          '&sender='+document.getElementById('sender').value +
          '&senderMail='+document.getElementById('sender').value +
          '&message='+document.getElementById('message').value +
          '&captcha='+document.getElementById('captcha').value;

    fetch(url).then(response => response.text()).then((data) =>
    {
      this.commentsBox.querySelector('#commentsContentBox').innerHTML = data;
    });

    ev.preventDefault();
    return false;
  }

}