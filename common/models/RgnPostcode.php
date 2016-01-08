<?php

namespace common\models;

use Yii;
use common\models\base\RgnPostcode as BaseRgnPostcode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rgn_postcode".
 *
 * @property string $statusLabel
 */
class RgnPostcode extends BaseRgnPostcode
{

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			/* default value */
			['status', 'default', 'value' => static::STATUS_ACTIVE],
			/* required */
			[['postcode', 'country_id'], 'required'],
			/* optional type */
			[['subdistrict_id', 'district_id', 'city_id', 'province_id'], 'safe'],
			/* field type */
			[['status'], 'string'],
			[['postcode', 'subdistrict_id', 'district_id', 'city_id', 'province_id', 'country_id', 'created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			/* value limitation */
			['status', 'in', 'range' => [
					self::STATUS_ACTIVE,
					self::STATUS_DELETED,
				]
			],
			[
				'country_id',
				'exist',
				'targetClass'		 => RgnCountry::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Country doesn't exist.",
			],
			[
				'province_id',
				'exist',
				'targetClass'		 => RgnProvince::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Province doesn't exist.",
			],
			[
				'city_id',
				'exist',
				'targetClass'		 => RgnCity::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "City doesn't exist.",
			],
			[
				'district_id',
				'exist',
				'targetClass'		 => RgnDistrict::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "District doesn't exist.",
			],
			[
				'subdistrict_id',
				'exist',
				'targetClass'		 => RgnSubdistrict::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Subdistrict doesn't exist.",
			],
		];

	}

	/*
	 * search data based on number & country
	 */

	static function findNumber($number, $country_id)
	{
		return static::findOne([
				'postcode'	 => $number,
				'country_id' => $country_id,
		]);

	}

	/*
	 * Revalidate and/or save postcode
	 */

	static function check($param = [])
	{
		$country_id = ArrayHelper::getValue($param, 'country_id');
		$postcode = ArrayHelper::getValue($param, 'postcode');

		if ($country_id > 0 && $postcode > 0)
		{
			$model = static::findNumber($postcode, $country_id);

			if (is_null($model))
			{
				$postcode = new RgnPostcode($param);

				return ($postcode->save(FALSE)) ? $postcode : NULL;
			}

			return $model->improveData($param);
		}

	}

	/*
	 * improve data & save it
	 */

	public function improveData($newData)
	{
		$improved = FALSE;
		$attributes = [
			'province_id',
			'city_id',
			'district_id',
			'subdistrict_id',
		];

		/*
		 * compare each attributes
		 */

		foreach ($attributes as $attr)
		{
			$oldValue = $this->getAttribute($attr);
			$newValue = ArrayHelper::getValue($newData, $attr);

			/*
			 * if old value is empty but new value exist, improve it
			 */

			if (empty($oldValue) && $newValue > 0)
			{
				$this->setAttribute($attr, $newValue);

				$improved = TRUE;
			}

			/*
			 * if old & new value exist but they are different, don't change any data, just leave it that way. improvement done.
			 */
			else if ($oldValue > 0 && $newValue > 0 && $oldValue != $newValue)
			{
				break;
			}
		}

		/*
		 * if data improved, save it
		 */

		if ($improved)
		{
			$this->save(FALSE);
		}

		return $this;

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'			 => 'ID',
			'status'		 => 'Status',
			'postcode'		 => 'Postcode',
			'subdistrict_id' => 'Subdistrict',
			'district_id'	 => 'District',
			'city_id'		 => 'City',
			'province_id'	 => 'Province',
			'country_id'	 => 'Country',
			'created_at'	 => 'Created At',
			'updated_at'	 => 'Updated At',
			'deleted_at'	 => 'Deleted At',
			'createdBy_id'	 => 'Created By',
			'updatedBy_id'	 => 'Updated By',
			'deletedBy_id'	 => 'Deleted By',
		];

	}

	public function getStatusLabel()
	{
		return parent::getStatusValueLabel($this->status);

	}

	public function delete()
	{
		$this->status = static::STATUS_ACTIVE;

		return parent::softDelete();

	}

	public function restore()
	{
		$this->status = static::STATUS_ACTIVE;

		return parent::restore();

	}

}