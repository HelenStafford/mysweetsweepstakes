
(function(){
    
    let chref = window.location.hash.substr(1);
    $( document ).ready(function() {
        if(chref != 'popup2'){
             window.location.replace(  window.location.href.hash = '#popup2' )
        }   
    });
    ///console.log( window.location );
})()