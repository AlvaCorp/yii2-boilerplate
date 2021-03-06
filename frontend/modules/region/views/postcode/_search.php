<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\depdrop\DepDrop;
use frontend\modules\region\models\Country;
use frontend\modules\region\models\search\PostcodeSearch;

/**
 * @var yii\web\View $this
 * @var frontend\modules\region\models\search\PostcodeSearch $model
 * @var yii\widgets\ActiveForm $form
 */

?>

<div class="postcode-search">

	<?php

	$form = ActiveForm::begin([
			'action' => ['index'],
			'method' => 'get',
	]);

	?>

	<?= $form->field($model, 'id') ?>

	<?= $form->field($model, 'status')->dropDownList(PostcodeSearch::optsstatus()); ?>

	<?= $form->field($model, 'postcode') ?>

	<?=

		$form
		->field($model, 'subdistrict_id')
		->widget(DepDrop::classname(), [
			'data'			 => [],
			'type'			 => DepDrop::TYPE_SELECT2,
			'select2Options' => [
				'pluginOptions' => [
					'multiple'			 => FALSE,
					'allowClear'		 => TRUE,
					'tags'				 => TRUE,
					'maximumInputLength' => 255, /* province name maxlength */
				],
			],
			'pluginOptions'	 => [
				'initialize'	 => TRUE,
				'placeholder'	 => 'Select or type subdistrict',
				'depends'		 => ['postcodeform-district_id'],
				'url'			 => Url::to([
					'/region/subdistrict/depdrop-options',
					'selected' => $model->subdistrict_id,
				]),
				'loadingText'	 => 'Loading subdistricts ...',
			],
	]);

	?>
	<?=

		$form
		->field($model, 'district_id')
		->widget(DepDrop::classname(), [
			'data'			 => [],
			'type'			 => DepDrop::TYPE_SELECT2,
			'select2Options' => [
				'pluginOptions' => [
					'multiple'			 => FALSE,
					'allowClear'		 => TRUE,
					'tags'				 => TRUE,
					'maximumInputLength' => 255, /* province name maxlength */
				],
			],
			'pluginOptions'	 => [
				'initialize'	 => TRUE,
				'placeholder'	 => 'Select or type district',
				'depends'		 => ['postcodeform-city_id'],
				'url'			 => Url::to([
					'/region/district/depdrop-options',
					'selected' => $model->city_id,
				]),
				'loadingText'	 => 'Loading districts ...',
			],
	]);

	?>
	<?=

		$form
		->field($model, 'city_id')
		->widget(DepDrop::classname(), [
			'data'			 => [],
			'type'			 => DepDrop::TYPE_SELECT2,
			'select2Options' => [
				'pluginOptions' => [
					'multiple'			 => FALSE,
					'allowClear'		 => TRUE,
					'tags'				 => TRUE,
					'maximumInputLength' => 255, /* province name maxlength */
				],
			],
			'pluginOptions'	 => [
				'initialize'	 => TRUE,
				'placeholder'	 => 'Select or type city',
				'depends'		 => ['postcodeform-province_id'],
				'url'			 => Url::to([
					'/region/city/depdrop-options',
					'selected' => $model->city_id,
				]),
				'loadingText'	 => 'Loading cities ...',
			],
	]);

	?>
	<?=

		$form
		->field($model, 'province_id')
		->widget(DepDrop::classname(), [
			'data'			 => [],
			'type'			 => DepDrop::TYPE_SELECT2,
			'select2Options' => [
				'pluginOptions' => [
					'multiple'			 => FALSE,
					'allowClear'		 => TRUE,
					'tags'				 => TRUE,
					'maximumInputLength' => 255, /* province name maxlength */
				],
			],
			'pluginOptions'	 => [
				'initialize'	 => TRUE,
				'placeholder'	 => 'Select or type province',
				'depends'		 => ['postcodeform-country_id'],
				'url'			 => Url::to([
					'/region/province/depdrop-options',
					'selected' => $model->province_id,
				]),
				'loadingText'	 => 'Loading provinces ...',
			],
	]);

	?>
	<?=

		$form
		->field($model, 'country_id')
		->widget(Select2::classname(), [
			'data'			 => Country::asOption(),
			'pluginOptions'	 =>
			[
				'placeholder'		 => 'Select or type Country',
				'multiple'			 => FALSE,
				'allowClear'		 => TRUE,
				'tags'				 => TRUE,
				'maximumInputLength' => 255, /* country name maxlength */
			],
	]);

	?>

	<div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
	</div>

	<?php ActiveForm::end(); ?>

</div>
