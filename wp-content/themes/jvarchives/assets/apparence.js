jQuery(document).ready(function($) {
    wp.customize('header_background', (value) => {
        value.bind(function (newVal) {
            $('.navbar').attr('style', 'background-color:' + newVal + '!important');
        })
    })
  });