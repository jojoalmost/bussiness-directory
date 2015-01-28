<?php
use \LaravelBook\Ardent\Ardent;
class Company extends  Ardent {
    public $autoHydrateEntityFromInput = true;    // hydrates on new entries' validation
    public $forceEntityHydrationFromInput = true; // hydrates whenever validation is called

	protected $fillable = ['name','contact_name','phone','fax','email','address_1','address_2'
        ,'postcode','city','website','short_description','description','tags','state_id']; //
    protected $guarded = ['id'];
    public static $customAttributes  = array(
        'name'=>'Company name'
    );

    public static $relationsData = array(
        'state' => array(self::BELONGS_TO,'States')
    );
    public static $rules = array(
        'name'                  => 'required|between:4,50',
    );
}