const scriptURL = 'https://script.google.com/macros/s/AKfycbxPlDAUWksQ9hv4e1uYnvvTKcP9VNKUksP_jHlSGkAHfJhwqhkg/exec'
const form = document.forms['google-sheet']

var sbsuccess = false;

form.addEventListener('submit', e => {
  e.preventDefault()
  fetch(scriptURL, { method: 'POST', body: new FormData(form)})
    .then(response => {alert("Thanks for Contacting us..! We Will Contact You Soon..."); 
      
      subscribe();
      })
    .catch(error => console.error('Error!', error.message))
}) 

///REDIRECT
function subscribe(){
  //window.open('http://google.com','w1')
  
  window.open('http://amazon.com','w22')
  window.location.assign('http://google.com')
}
