$(function() {
    var langForm = document.querySelector('#language-form');
    var langSwitcher = document.querySelector('#language');

    $('#language').on('change', e => {
        langForm.submit();
    });
    /*langSwitcher.on('onchange', e =>{
        console.log(e);
    });*/
});
