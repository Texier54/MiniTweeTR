<?php

	namespace tweeterapp\model;

	class Follow extends \Illuminate\Database\Eloquent\Model {

		protected $table = 'follow';
		protected $primaryKey = 'id';
		public $timestamps = false;

	}
