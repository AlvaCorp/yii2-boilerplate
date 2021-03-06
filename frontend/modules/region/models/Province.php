<?php

namespace frontend\modules\region\models;

use Yii;
use frontend\modules\region\models\operation\ProvinceOperation;

/**
 * This is the base-model class for table "rgn_province".
 *
 * @property integer $id
 * @property string $status
 * @property string $number
 * @property string $name
 * @property string $abbreviation
 * @property integer $country_id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $deleted_at
 * @property integer $createdBy_id
 * @property integer $updatedBy_id
 * @property integer $deletedBy_id
 *
 * @property string $statusLabel
 * @property String $linkTo
 *
 * @property Country $country
 *
 * @property City[] $rgnCities
 * @property Postcode[] $rgnPostcodes
 */
class Province extends \common\base\Model
{

	/**
	 * ENUM field values
	 */
	const STATUS_ACTIVE = 'active';

	const STATUS_DELETED = 'deleted';

	var $enum_labels = false;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->operation = new ProvinceOperation([
			'model' => $this
		]);

	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'rgn_province';

	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			/* default value */
			['status', 'default', 'value' => static::STATUS_ACTIVE],
			/* required */
			[['name', 'country_id'], 'required'],
			/* field type */
			[['status'], 'string'],
			[['number', 'abbreviation'], 'string', 'max' => 32],
			[['name'], 'string', 'max' => 255],
			[['created_at', 'updated_at', 'deleted_at', 'createdBy_id', 'updatedBy_id', 'deletedBy_id'], 'integer'],
			/* value limitation */
			['status', 'in', 'range' => [
					static::STATUS_ACTIVE,
					static::STATUS_DELETED,
				],
			],
			[
				'country_id',
				'exist',
				'targetClass'		 => Country::className(),
				'targetAttribute'	 => 'id',
				'when'				 => function ($model, $attribute)
				{
					return is_numeric($model->$attribute);
				},
				'message' => "Country doesn't exist.",
			],
		];

	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'			 => 'ID',
			'status'		 => 'Status',
			'number'		 => 'Number',
			'name'			 => 'Name',
			'abbreviation'	 => 'Abbreviation',
			'country_id'	 => 'Country',
			'created_at'	 => 'Created At',
			'updated_at'	 => 'Updated At',
			'deleted_at'	 => 'Deleted At',
			'createdBy_id'	 => 'Created By',
			'updatedBy_id'	 => 'Updated By',
			'deletedBy_id'	 => 'Deleted By',
		];

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCities()
	{
		return $this
				->hasMany(City::className(), ['province_id' => 'id'])
				->andFilterWhere(['like', 'status', City::STATUS_ACTIVE]);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getPostcodes()
	{
		return $this
				->hasMany(Postcode::className(), ['province_id' => 'id'])
				->andFilterWhere(['like', 'status', Postcode::STATUS_ACTIVE]);

	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCountry()
	{
		return $this->hasOne(Country::className(), ['id' => 'country_id']);

	}

	/**
	 * get column status enum value label
	 * @param string $value
	 * @return string
	 */
	public static function getStatusValueLabel($value)
	{
		$labels = self::optsStatus();
		if (isset($labels[$value]))
		{
			return $labels[$value];
		}
		return $value;

	}

	/**
	 * column status ENUM value labels
	 * @return array
	 */
	public static function optsStatus()
	{
		return [
			self::STATUS_ACTIVE	 => 'Active',
			self::STATUS_DELETED => 'Deleted',
		];

	}

	/**
	 * generate regular link to view model detail
	 *
	 * @param array $linkOptions
	 * @return string
	 */
	public function getLinkTo($linkOptions = ['title' => 'view province detail', 'data-pjax' => 0])
	{
		return $this->operation->getLinkView('', $linkOptions);

	}

	/**
	 * get status label
	 *
	 * @return string
	 */
	public function getStatusLabel()
	{
		return static::getStatusValueLabel($this->status);

	}

	/**
	 * @inheritdoc
	 */
	public function delete()
	{
		$this->status = static::STATUS_ACTIVE;

		return parent::softDelete();

	}

	/**
	 * @inheritdoc
	 */
	public function restore()
	{
		$this->status = static::STATUS_ACTIVE;

		return parent::restore();

	}

}
