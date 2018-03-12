(function ($) {
  'use strict';
  Drupal.behaviors.ejikznaykaImage = {
    attach: function (context, settings) {
      $('button.ejikznayka_page', context).click(function () {
        $(this).next('.overlay').css('display', 'flex');
        $(this).next('.overlay').css('font-size', settings.ejikznayka.display_settings.font_size + 'px');
        $(this).next('.overlay').children('.field--name-field-paragraph-image-based-text').addClass('ejikznayka-top ejikznayka-left');
        $(this).next('.overlay').children('.field--name-field-paragraph-image-based-tex1').addClass('ejikznayka-bottom ejikznayka-left');
        $(this).next('.overlay').children('.field--name-field-paragraph-image-based-tex2').addClass('ejikznayka-bottom ejikznayka-right');
        $(this).next('.overlay').children('.field--name-field-paragraph-image-based-tex3').addClass('ejikznayka-top ejikznayka-right');
        const $self = $(this);
        setTimeout(function () {
          $self.next('.overlay').css('display', 'none');
          $self.parent('.field__item').next('.field__item').children('button.ejikznayka_page').click();
        },
        settings.ejikznayka.display_settings.interval * 1000);
      });
    }
  };
})(jQuery);
