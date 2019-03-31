<?php
/**
.---------------------------------------------------------------------.
|  @package: Lazy Footer (a.k.a. add-on Footer)
|  @version: v1.0.9 (31 March 2019)
|  @link:    http://italinux.com/addon-footer
|  @docs:    http://italinux.com/addon-footer/docs
|
|  @author: Matteo Montanari <matteo@italinux.com>
|  @link:   http://matteo-montanari.com
'---------------------------------------------------------------------'
.---------------------------------------------------------------------------.
| @copyright (c) 2019                                                       |
| ------------------------------------------------------------------------- |
| @license: Concrete5.org Marketplace Commercial Add-Ons & Themes License   |
|           http://concrete5.org/help/legal/commercial_add-on_license       |
|           or just: file://lazy_footer/LICENSE.TXT                         |
|                                                                           |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
'---------------------------------------------------------------------------'
*/
defined('C5_EXECUTE') or die("Access Denied.");
?>

<div class="<?php echo $btWrapperForm ?>">
  <section>
    <div>
      <div class="row main">
        <div class="col-lg-9 col-sm-8 col-xs-12">

          <div class="row">
            <div class="col-lg-8 col-lg-offset-2">
              <div class="form-group center single-space-top">
                <?php echo $form->label('title', t('Title: %s', '<span>(' . t('thanks for passing by..') . ')</span>'))?>
                <div class="input-group center p70">
                  <?php echo $form->text('title', $title, array('maxlength' => '50'))?>
                </div>
              </div>
              <div class="form-group center single-space-bottom">
                <?php echo $form->label('subtitle', t('Subtitle: %s', '<span>(' . t('Come back soon') . ')</span>'))?>
                <div class="input-group center p90">
                  <?php echo $form->text('subtitle', $subtitle, array('maxlength' => '50'))?>
                </div>
              </div>
            </div>
            <div class="col-lg-2">
              <div class="form-group center single-margin-top no-paddings">
                <?php echo $form->label('isQuoted', t('wrap title with Quotes?'))?>
                <div class="input-group">
                  <div class="radio">
                    <label>
                      <?php echo $form->radio('isQuoted', 1, (int) $isQuoted)?>
                      <span><?php echo t('Yes')?></span>
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <?php echo $form->radio('isQuoted', 0, (int) $isQuoted)?>
                      <span><?php echo t('No')?></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

<!-- START row-->
          <div class="col-lg-12 wrap-tab-2">
            <?php
              echo $hUI->tabs(array(
                  array('item_1', t('Credits %s', 1), true),
                  array('item_2', t('Credits %s', 2)),
              ));
            ?>
<!-- Credit one -->
            <div class="ccm-tab-content" id="ccm-tab-content-item_1">
              <fieldset>
                <legend>&nbsp;</legend>

                <div class="row">
                  <div class="col-lg-8">
                    <div class="form-group center">
                      <?php echo $form->label('credits1stMessage', t('Credits: %s', '<span>(' . t('to see our Terms & Conditions..') . ')</span>'))?>
                      <div class="input-group">
                        <?php echo $form->text('credits1stMessage', $credits1stMessage, array('maxlength' => '255'))?>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group center no-paddings">
                      <?php echo $form->label('credits1stName', t('Text: %s', '<span>(' . t('click here') . ')</span>'))?>
                      <div class="input-group align-center">
                        <?php echo $form->text('credits1stName', $credits1stName, array('maxlength' => '255'))?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group center">
                    <?php echo $form->label('credits1stUrl', t('Link: %s', '<span>(' . t('http://%1$s.website/%2$s-%3$s-%4$s', t('your'), t('terms'), t('and'), t('conditions')) . ')</span>'))?>

                    <div class="input-group center p80">
                      <?php echo $form->text('credits1stUrl', $credits1stUrl, array('maxlength' => '255', 'placeholder' => t('http://blah-blah.com/%1$s-%2$s', t('new'), t('page'))))?>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>

<!-- Credit two -->
            <div class="ccm-tab-content" id="ccm-tab-content-item_2">
              <fieldset>
                <legend class="center-and-bold">
                  <span class="nota-bene"><?php echo t('it shows at the very bottom')?></span>
                </legend>

                <div class="row">
                  <div class="col-lg-8">
                    <div class="form-group center">
                      <?php echo $form->label('credits2ndMessage', t('Credits: %s', '<span>(' . t('developed for fun by:') . ')</span>'))?>
                      <div class="input-group">
                        <?php echo $form->text('credits2ndMessage', $credits2ndMessage, array('maxlength' => '255'))?>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group center no-paddings">
                      <?php echo $form->label('credits2ndName', t('Name: %s', '<span>(John)</span>'))?>
                      <div class="input-group align-center">
                        <?php echo $form->text('credits2ndName', $credits2ndName, array('maxlength' => '255'))?>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group center">
                    <?php echo $form->label('credits2ndUrl', t('Link: %s', '<span>(http://my-url.ext)</span>'))?>

                    <div class="input-group center p80">
                      <?php echo $form->text('credits2ndUrl', $credits2ndUrl, array('maxlength' => '255', 'placeholder' => t('http://blah-blah.com/%1$s-%2$s', t('new'), t('page'))))?>
                    </div>
                  </div>
                </div>
              </fieldset>
            </div>
          </div>
<!-- END row -->

        </div>
        <div class="col-lg-3 col-sm-4 col-xs-12">
          <section class="style">
            <div>
              <div class="row main">
                <div class="title">
                  <?php echo t('Customise Style')?>
                </div>
                <div class="col-lg-12">
                  <div class="form-group center light-title single-space-top no-sides-paddings single-space-bottom">
                    <?php echo $form->label('bgColorRGBA', t('background colour %s', '<br /><span>(' . t('with or without transparency') . ')</span>'))?>
                    <div class="input-group">
                      <!-- Show a Color Palette in RGB Color Format with Transparency Slider (RGBA) -->
                      <?php $color->output('bgColorRGBA', $bgColorRGBA, $bgColorPalette)?>
                    </div>
                  </div>
                  <div class="form-group center light-title no-margins no-sides-paddings single-space-bottom">
                    <?php echo $form->label('bgColorOpacity', t('adjust top opacity'))?>
                    <div class="input-group">
                      <!-- Adjust Background Color (top) Opacity: Over Image -->
                      <?php
                        if (is_array($bgColorOpacityOptions)) {
                          foreach ($bgColorOpacityOptions as $key => $value) {
                        ?>
                      <div class="col-xs-3 no-paddings">
                        <div class="radio">
                          <span style="color:#333"><?php echo t($key)?></span>
                          <br />
                          <label>
                            <?php echo $form->radio('bgColorOpacity', $value, (float) $bgColorOpacity)?>
                          </label>
                        </div>
                      </div>
                      <?php
                          }
                        }
                        ?>
                    </div>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group center light-title single-space-top single-space-bottom">
                    <?php echo $form->label('bgFID', t('background image'))?>
                    <div class="input-group">
                      <?php echo $asset->image('ccm-b-image-bgFID', 'bgFID', t('Choose Image'), $bgFID, array())?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group center light-title single-space-bottom">
                    <?php echo $form->label('fgColorRGB', t('font colour'))?>
                    <div class="input-group">
                      <!-- Show a Color Palette in RGB Color Format -->
                      <?php $color->output('fgColorRGB', $fgColorRGB, $fgColorPalette)?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <div class="form-group center light-title single-space-bottom">
                    <?php echo $form->label('isAnimated', t('with Animation'))?>
                    <div class="input-group">
                      <div class="radio col-sm-6 col-sm-offset-0 col-xs-3 col-xs-offset-3">
                        <label>
                          <?php echo $form->radio('isAnimated', 1, (int) $isAnimated)?>
                          <span class"on"><?php echo t('Yes')?></span>
                        </label>
                      </div>
                      <div class="radio col-sm-6 col-xs-3">
                        <label>
                          <?php echo $form->radio('isAnimated', 0, (int) $isAnimated)?>
                          <span class="off"><?php echo t('No')?></span>
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>

        </div>
      </div>
    </div>
  </section>
</div>
