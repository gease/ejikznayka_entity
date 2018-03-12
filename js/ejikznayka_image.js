(function ($) {
  'use strict';
  Drupal.behaviors.ejikznaykaImage = {
    attach: function (context, settings) {
      $('button.ejikznayka_page', context).click(function () {
        $(this).next('.overlay').css('display', 'flex');
        $(this).next('.overlay').css('font-size', settings.ejikznayka.display_settings.font_size + 'px');
        $(this).next('.overlay').children('.field--name-field-paragraph-image-based-text').addClass('ejikznayka-top ejikznayka-left');
        const $self = $(this);
        setTimeout(function () {
          $self.next('.overlay').css('display', 'none');
          console.log($self.parent('.field__item').next('.field__item').children('button.ejikznayka_page'));
          $self.parent('.field__item').next('.field__item').children('button.ejikznayka_page').click();
        }, settings.ejikznayka.display_settings.interval * 1000);
        //console.log(settings);
        //$(this).next('.overlay').children('div:first-of-type').show();
      });
      /*$('.overlay', context).click(function () {
        //$(this).children('div:visible').hide().next().not('.field--name-field-paragraph-image-based-soun').show();
        $(this).children('.field--name-field-paragraph-image-based-text').addClass(['ejikznayka-top', 'ejikznayka-left']);
        if ($(this).children('div:visible').length === 0) {
          $(this).children('.field--name-field-paragraph-image-based-soun').find('audio').get(0).play();
          $(this).hide();

        }
      });*/
    }
  };
})(jQuery);
